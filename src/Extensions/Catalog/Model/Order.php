<?php

namespace App\Extensions\Catalog\Model;

use Simflex\Core\Buffer;
use Simflex\Core\DB\AQ;
use Simflex\Core\ModelBase;
use Simflex\Core\Models\User;

class Order extends ModelBase
{
    protected static $table = 'catalog_order';
    protected static $primaryKeyName = 'order_id';

    protected $oldStatus = '';

    protected function afterFill()
    {
        $this->oldStatus = $this->status;
    }

    protected function beforeSave()
    {
        if ($this->oldStatus != $this->status) {
            $this->edited_on = time();
        }

        return parent::beforeSave();
    }

    protected function afterSave($success)
    {
        if (!$success) {
            return;
        }

        if ($this->status == 'finished') {
            $prods = $this->getProducts();
            foreach ($prods as $prod) {
                $prod['product']->removeStock();
            }
        } else if ($this->status == 'canceled') {
            $prods = $this->getProducts();
            foreach ($prods as $prod) {
                $prod['product']->unlockStock();
            }
        }
    }

    public function getProducts(string $limit = '', string $sort = '', string $filter = '')
    {
        $out = [];

        $q = (new AQ())->from('catalog_order_product')->where(['order_id' => $this->order_id])->asArray();
        if ($limit) {
            $q = $q->limit($limit);
        }

        if ($sort) {
            $q = $q->orderBy($sort);
        }

        if ($filter) {
            $q = $q->andWhere($filter);
        }

        foreach ($q->all() as $r) {
            $out[] = [
                'id' => $r['order_product_id'],
                'product' => Product::findOne(['product_id' => $r['product_id']]),
                'qty' => $r['qty'],
                'size' => $r['size'],
                'stock' => $r['stock'],
                'is_changed' => $r['is_changed'],
                'is_added' => $r['is_added'],
                'is_deleted' => $r['is_deleted'],
                'price' => $r['price'],
                'price_old' => $r['price_old'],
            ];
        }

        return $out;
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

    public function getTotal3()
    {
        return number_format($this->__sum_actual, 2, '.', ' ');
    }

    public function getTotal2()
    {
        return number_format($this->getTotalNum(), 2, '.', ' ');
    }

    public function getTotalNum()
    {
        return $this->__sum_actual - ($this->__sum_actual * ($this->discount / 100));
    }

    public function getTotalNum2()
    {
        return $this->__sum_actual;
    }

    public function rebuildData()
    {
        $st = 0;
        $sa = 0;
        foreach ($this->getProducts('', '', 'COALESCE(is_deleted, 0) = 0') as $prod) {
            $total = $prod['qty'] * ((!$prod['price_old'] || $prod['price_old'] == $prod['price'] || $prod['price_old'] < $prod['price']) ?
                    $prod['price'] : $prod['price_old']);
            $st += $total;
            $sa += $prod['qty'] * $prod['price'];
        }

        $this->sum_actual = $sa;
        $this->sum_total = $st;
        $this->save();
        $this->reload();
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

    public function offsetGetUser()
    {
        return User::findOne(['user_id' => $this->user_id]);
    }

    public function getTotalProductCount()
    {
        return Buffer::getOrSet('ordtot.' . $this->id, function () {
            return (new AQ())->from('catalog_order_product')->where('(is_deleted != 1 or is_deleted is null)')
                ->andWhere(['order_id' => $this->order_id])
                ->select('sum(qty)')->fetchScalar() ?? 0;
        });
    }

    public function getProductCount($filter = '')
    {
        $q = (new AQ())->from('catalog_order_product')
            ->where(['order_id' => $this->order_id]);

        if ($filter) {
            $q = $q->andWhere($filter);
        }

        return $q->select('count(*)')->fetchScalar() ?? 0;
    }
}