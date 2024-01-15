<?php

namespace App\Extensions\Catalog\Model;

use App\Extensions\Catalog\CategoryAssist;
use App\Extensions\Catalog\SessionAssist;
use Simflex\Core\Buffer;
use Simflex\Core\DB;
use Simflex\Core\ModelBase;
use Simflex\Core\Profiler;

class Product extends ModelBase
{
    protected static $table = 'catalog_product';
    protected static $primaryKeyName = 'product_id';

    public function addToCategory(int $categoryId)
    {
        if (P2c::findOne(['product_id' => $this->id, 'category_id' => $categoryId])) {
            return;
        }

        P2c::insertStatic(['product_id' => $this->id, 'category_id' => $categoryId]);
    }

    public function removeFromCategory(int $categoryId)
    {
        $p2c = P2c::findOne(['product_id' => $this->id, 'category_id' => $categoryId]);
        if ($p2c) {
            $p2c->delete();
        }
    }

    public function recalcStock()
    {
        $cnt = 0;
        foreach ($this->getSizes() as $sz) {
            $cnt += $sz['stock'];
        }

        $this->stock = $cnt;
    }

    public function inFav(): bool
    {
        return SessionAssist::$fav->inFav($this->product_id);
    }

    public function inSale(): bool
    {
        $sales = Buffer::getOrSet('prod.sale', function () {
            $saleCats = implode(',', array_map(function ($c) {
                return $c['category_id'];
            }, (new DB\AQ())->from('catalog_category')
                ->where('is_sale = 1')
                ->select('category_id')
                ->asArray()
                ->all()));

            $saleProds = array_map(function ($c) {
                return $c['product_id'];
            }, (new DB\AQ())->from('catalog_p2c')
                ->where('category_id in (' . $saleCats . ')')
                ->select('product_id')
                ->asArray()
                ->all());

            return $saleProds;
        });

        return in_array($this->id, $sales);
    }

    public function getSizesRaw()
    {
        return json_decode($this->size, true)['v'] ?? [];
    }

    public function getSizes()
    {
        $a = array_filter(json_decode($this->size, true)['v'] ?? [], function ($i) {
            return $i['stock'] > 0;
        });

        usort($a, function ($a, $b) {
            return (int)$a['size'] > (int)$b['size'];
        });
        return $a;
    }

    public function getSizesStr()
    {
        return implode(', ', array_map(function ($c) {
            return $c['size'];
        }, $this->getSizes()));
    }

    public function getPreviewImage()
    {
        return $this->getImages()[0];
    }

    public function getParams()
    {
        $out = [];

        $q = DB::query('select cpp.name, value from catalog_product_param_value 
    left join catalog_product_param cpp on cpp.param_id = catalog_product_param_value.param_id where product_id = ?', [$this->id]);
        while ($r = DB::fetch($q)) {
            $out[] = [
                'name' => $r['name'],
                'values' => explode(',', $r['value'])
            ];
        }

        return $out;
    }

    protected function genPPReadyInfo($obj)
    {
        return [
            'id' => $obj->product_id,
            'name' => $obj->name,
            'path' => '/' . $obj->path,
            'img' => $obj->getPreviewImage(),
            'price' => $obj->price,
            'price_old' => $obj->price_old,
            'stock' => $obj->stock,
            'sizes' => $obj->getSizes(),
            'color' => $obj->color,
            'fav' => $obj->inFav(),
            'sale' => !!$obj->bk_enable,
        ];
    }

    public function getSaleName()
    {
        return Buffer::getOrSet('cursale', function () {
            return DB::result('select name from catalog_sale where is_running = 1 limit 1', 0);
        });
    }

    public function getPPReadyInfo2()
    {
        $info = $this->genPPReadyInfo($this);
        foreach ($this->getLinked() as $lnk) {
            $info['colors'][] = $this->genPPReadyInfo($lnk);
        }

        return $info;
    }

    public function getPPReadyInfo()
    {
        $info = $this->genPPReadyInfo($this);
        foreach ($this->getLinked() as $lnk) {
            $info['colors'][] = $this->genPPReadyInfo($lnk);
        }

        return htmlspecialchars(json_encode($info, JSON_UNESCAPED_UNICODE));
    }

    public function getPriceFmt(string $p = 'price')
    {
        return number_format($this->{'__' . $p}, 2, '.', ' ');
    }

    public function offsetGet($offset)
    {
        if (in_array($offset, ['__price', '__price_old', '__price_base'])) {
            return parent::offsetGet(substr($offset, 2));
        }

        $val = parent::offsetGet($offset);
        if (in_array($offset, ['price', 'price_old', 'price_base'])) {
            return number_format((float)$val, 0, '.', ' ');
        }

        return $val;
    }

    public function getImages()
    {
        $data = json_decode($this->photo, true);
        if (!$data || !isset($data['v']) || !$data['v']) {
            return [asset('img/default-img.png', true)];
        }

        $v = $data['v'];
        usort($v, function ($a, $b) {
            return $a['order'] > $b['order'];
        });

        return array_map(function ($e) {
            return $e['img'];
        }, $v);
    }

    protected function getLinkQuery()
    {
        return (new DB\AQ())->from('catalog_product', 't')
            ->select('distinct t.*')
            ->custom("INNER JOIN catalog_p2p p
ON (p.left_id = t.product_id AND p.right_id = {$this->id})
   OR (p.right_id = t.product_id AND p.left_id = {$this->id})
WHERE t.is_active = 1 AND t.product_id != {$this->id}");
    }

    public function getLinked()
    {
        return CategoryAssist::$linkPaths[$this->id] ?? [];

//        return Buffer::getOrSet('prod.links.' . $this->id, function () {
//            return $this->getLinkQuery()
//                ->setModelClass(static::class)
//                ->all();
//        });
    }

    public function getLinkedPaths()
    {
        return array_map(function ($p) {
            return ['path' => $p->path, 'color' => $p->color];
        }, CategoryAssist::$linkPaths[$this->id] ?? []);

//        return Buffer::getOrSet('prod.links_paths.' . $this->id, function () {
//            return $this->getLinkQuery()
//                ->select(['t.path', 't.color'])
//                ->asArray()
//                ->all();
//        });
    }

    public function getTotalStock()
    {
        return $this->stock;
    }

    public function getAllCategories()
    {
        return Category::findAdv()
            ->where('category_id in (select category_id from catalog_p2c where product_id = ' . $this->id . ')')
            ->all();
    }

    public function inCategory(int $categoryId)
    {
        return !!P2c::findOne(['product_id' => $this->id, 'category_id' => $categoryId]);
    }

    public function getRelative()
    {
        $orderBy = 'is_popular desc';
        $cats = $this->getAllCategories();

        $myCats = [];
        foreach ($cats as $cat) {
            $myCats[] = $cat->category_id;
        }

        $bestCat = 0;
        $bestCatObj = null;
        if ($cats) {
            foreach ($cats as $cat) {
                if ($cat->getProductCount() >= 5 && !$cat->is_sale) {
                    $bestCat = $cat->category_id;
                    $bestCatObj = $cat;
                    break;
                }
            }
        }

        if ($bestCat) {
            $myCats = $bestCatObj->getAllChildren();
            if (!$myCats) {
                $myCats[] = $bestCat;
            }
        }

        $q = self::findAdv();
        if ($myCats) {
            $q = $q->where('product_id in (select pts2.product_id from catalog_p2c pts2 where pts2.category_id in (' . implode(',', $myCats) . '))');
        }

        $q->andWhere('product_id != ' . $this->id);
        $q->andWhere('is_active = 1 and stock > 0');
        return $q->orderBy($orderBy)->limit(10)->all();
    }

    public function lockStock($delta)
    {
        DB::query('update catalog_stock set available = available - ?, in_orders = in_orders + ? where product_id = ?', [$delta, $delta, $this->id]);
    }

    public function unlockStock($delta)
    {

        DB::query('update catalog_stock set available = available + ?, in_orders = in_orders - ? where product_id = ?', [$delta, $delta, $this->id]);
    }

    public function removeStock($delta)
    {
        DB::query('update catalog_stock set in_orders = in_orders - ? where product_id = ?', [$delta, $delta, $this->id]);
    }
}