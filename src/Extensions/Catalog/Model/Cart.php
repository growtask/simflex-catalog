<?php
namespace App\Extensions\Catalog\Model;

use App\Extensions\Catalog\SessionAssist;
use Simflex\Core\Buffer;
use Simflex\Core\Container;
use Simflex\Core\DB;
use Simflex\Core\DB\AQ;
use Simflex\Core\ModelBase;

class Cart extends ModelBase
{
    protected static $table = 'catalog_cart';
    protected static $primaryKeyName = 'cart_id';

    public static function getOrInsert(int $id = 0)
    {
        return Buffer::getOrSet('cart', function () use ($id) {
            $cart = self::findOne(['sess_id' => SessionAssist::getId()]);
            if (!$cart) {
                if (($cart = self::findOne(['user_id' => $id]))) {
                    $cart->sess_id = SessionAssist::getId();
                    $cart->save();
                    $cart->reload();
                    return $cart;
                }

                $cart = new self;
                $id ? ($cart->user_id = $id) : ($cart->sess_id = SessionAssist::getId());
                $cart->sum_total = 0;
                $cart->sum_actual = 0;
            } else {
                // fixes a bug that you get your cart emptied per login
                if ($id && ($cart2 = self::findOne(['user_id' => $id]))) {
                    $cart2->sess_id = SessionAssist::getId();
                    $cart2->save();
                    $cart2->reload();
                    return $cart2;
                }

                if ($id && !$cart->user_id) {
                    DB::query('update catalog_cart set user_id = 0 where user_id = ?', [$id]);
                }

                $cart->user_id = $id;
            }

            $cart->save();
            $cart->reload();

            return $cart;
        });
    }

    public function inCart(int $prod, string $size = ''): bool
    {
        $in = Buffer::getOrSet('cart.inarr', function () {
            $arr = [];

            $q = DB::query('select product_id, size from catalog_cart_product where cart_id = ?', [$this->cart_id]);
            while ($r = DB::fetch($q)) {
                $arr[] = md5($r['product_id'] . '.');
                foreach (json_decode($r['size'], true)['v'] ?? [] as $s) {
                    $arr[] = md5($r['product_id'] . '.' . $s);
                }
            }

            return $arr;
        });

        return in_array(md5($prod . '.' . $size), $in, true);
    }

    public function offsetGet($offset)
    {
        $fmt = true;
        if (str_starts_with($offset, '__')) {
            $fmt = false;
            $offset = substr($offset, 2);
        }

        $val = parent::offsetGet($offset);
        if ($fmt && in_array($offset, ['sum_total', 'sum_actual'])) {
            return number_format((float)$val, 0, '', ' ');
        }

        return $val;
    }

    public function addOrUpdateProduct(int $id, string $size, int $qty, bool $ignoreStock = false, bool $clampAmount = false): bool
    {
        $pq = DB::query('select price, price_old, stock, size from catalog_product where product_id = ? and is_active = 1', [$id]);
        $pq = DB::fetch($pq);

        if (!$pq || (!$ignoreStock && !$pq['stock'])) {
            // oh, no! we don't have a product!
            return false;
        }

        if ($clampAmount && $pq['stock'] < $qty) {
            $qty = max($pq['stock'], 0);
        }

        // check size stock
        foreach (json_decode($pq['size'], true)['v'] ?? [] as $szv) {
            if ($szv['size'] == $size && !$szv['stock'] && !$ignoreStock) {
                return false;
            }

            if ($szv['size'] == $size && $szv['stock'] < $qty && $clampAmount) {
                $qty = max($szv['stock'], 0);
            }
        }

        $q = DB::query('select cart_product_id from catalog_cart_product where cart_id = ? and product_id = ? and size = ?', [$this->cart_id, $id, $size]);
        $r = DB::fetch($q);

        $total = (!$pq['price_old'] || $pq['price_old'] == $pq['price'] || $pq['price_old'] < $pq['price']) ? $pq['price'] : $pq['price_old'];
        if (!$r) {
            DB::query('insert into catalog_cart_product (cart_id, product_id, sum_total, sum_actual, qty, size) values (?, ?, ?, ?, ?, ?)', [
                $this->cart_id, $id, (float)($total) * $qty, $pq['price'] * $qty, $qty, $size
            ]);
        } else {
            DB::query('update catalog_cart_product set sum_total = ?, sum_actual = ?, qty = ? where cart_id = ? and product_id = ? and size = ?', [
                (float)($total) * $qty, $pq['price'] * $qty, $qty, $this->cart_id, $id, $size
            ]);
        }

        $this->rebuildData();
        return true;
    }

    public function removeProduct(int $id, string $size, bool $rebuild = true)
    {
        DB::query('delete from catalog_cart_product where cart_id = ? and product_id = ? and size = ?', [$this->cart_id, $id, $size]);
        if ($rebuild) {
            $this->rebuildData();
        }
    }

    public function rebuildData()
    {
        DB::query('update catalog_cart set sum_actual = (select sum(sum_actual) from catalog_cart_product where cart_id = ?), sum_total = (select sum(sum_total) from catalog_cart_product where cart_id = ?) where cart_id = ?', [$this->cart_id, $this->cart_id, $this->cart_id]);
        $this->reload();
    }

    public function hasAnyUnavailable(): bool
    {
        foreach ($this->getProducts()['items'] as $p) {
            if (!$p['product']->stock) {
                return true;
            }

            foreach ($p['product']->getSizesRaw() as $sz) {
                if ($sz['size'] == $p['size'] && !$sz['stock']) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getProducts(string $limit = '', string $order = ''): array
    {
        return Buffer::getOrSet('cart.prods.' . md5($limit . '.' . $order), function () use ($limit, $order) {
            $total = $this->getProductCount();
            $all = $this->getQuery($limit, $order)->asArray()->all();

            $ret = [
                'total' => $total,
                'items' => [],
            ];

            foreach ($all as $i) {
                $ret['items'][] = [
                    'sum_total' => $i['sum_total'],
                    'sum_actual' => $i['sum_actual'],
                    'qty' => $i['qty'],
                    'size' => $i['size'],
                    'product' => Product::findOne(['product_id' => $i['product_id']]),
                ];
            }

            return $ret;
        });
    }

    public function getRelativeProducts()
    {
        $ourcats = [];
        $q = (new AQ())->from('catalog_p2c', 'p2c')
            ->where('p2c.product_id in (select product_id from catalog_cart_product where cart_id = ' . $this->cart_id . ')')
            ->asArray()
            ->all();

        foreach ($q as $qq) {
            if (DB::result('select is_sale from catalog_category where category_id = ?', 0, [$qq['category_id']])) {
                continue;
            }

            $ourcats[] = $qq['category_id'];
        }

        if (!$ourcats) {
            $ourcats[] = $q[0]['category_id'];
        }

        $ourcats = implode(',', array_unique($ourcats));

        $q = "SELECT cp.*
FROM catalog_product cp
JOIN catalog_p2c cp2c ON cp.product_id = cp2c.product_id
LEFT JOIN catalog_cart_product ccp ON cp.product_id = ccp.product_id AND ccp.cart_id = {$this->cart_id}
WHERE cp.is_active = 1
  AND cp.stock > 0
  AND cp2c.category_id in ($ourcats)
  AND ccp.product_id IS NULL
ORDER BY cp.is_popular DESC, cp.is_new DESC
LIMIT 25;";

        $out = [];
        $q = DB::query($q);
        while ($r = DB::fetch($q)) {
            $p = new Product();
            $p->fill($r);
            $out[] = $p;
        }

        return $out;
    }

    public function getTotalOrderCount()
    {
        return Buffer::getOrSet('carttot.' . $this->id, function () {
            return $this->getQuery()->select('sum(qty)')->fetchScalar() ?? 0;
        });
    }

    public function getProductCount()
    {
        return Buffer::getOrSet('cartcnt.' . $this->id, function () {
            return $this->getQuery()->select('count(*)')->fetchScalar() ?? 0;
        });
    }

    protected function getQuery(string $limit = '', string $order = '')
    {
        $q = (new AQ())->from('catalog_cart_product')
            ->where(['cart_id' => $this->cart_id]);

        if ($order) {
            $q = $q->orderBy('(select stock from catalog_product where catalog_product.product_id = catalog_cart_product.product_id) ' . $order);
        }

        return $q->limit($limit);
    }

    public function getDiscount()
    {
        return number_format($this->getDiscountNum(), 0, '', ' ');
    }

    public function getDiscountNum()
    {
        return $this->__sum_total - $this->getTotalNum();
    }

    public function getTotal()
    {
        return number_format($this->getTotalNum(), 0, '', ' ');
    }

    public function getTotal2()
    {
        return number_format($this->getTotalNum(), 2, '.', ' ');
    }

    public function getTotalNum()
    {
        $u = Container::getUser();
        return $this->__sum_actual - ($this->__sum_actual * (($u->discount ?? 0) / 100));
    }
}