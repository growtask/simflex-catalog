<?php
namespace App\Extensions\Catalog\Admin;

use App\Extensions\Catalog\CacheGen;
use Simflex\Admin\Base;
use Simflex\Core\DB;

class Params extends Base
{
    protected function deleteItem($id)
    {
        DB::query('delete from catalog_product_param_cat where param_id = ?', [$id]);
        DB::query('delete from catalog_product_param_value where param_id = ?', [$id]);

        $g = new CacheGen();
        $g->updateAll();

        return parent::deleteItem($id);
    }

    public function save()
    {
        $ret = parent::save();

        $g = new CacheGen();
        $g->updateAll();

        return $ret;
    }
}