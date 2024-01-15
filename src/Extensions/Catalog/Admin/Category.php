<?php
namespace App\Extensions\Catalog\Admin;

use App\Extensions\Catalog\CategoryAssist;
use Simflex\Admin\Base;
use Simflex\Core\DB;
use Simflex\Core\Helpers\Str;

class Category extends Base
{
    protected function initTable()
    {
        parent::initTable();
        $this->isHierarchy = false;
    }

    public function save()
    {
        if (!$_POST['alias']) {
            $_POST['alias'] = Str::translite($_POST['name']);
        }

        $_POST['path'] = 'catalog/' . $_POST['alias'];

        $this->fields['path']->readonly = false;
        $ret = parent::save();
        CategoryAssist::generateCountCache();
        return $ret;
    }

    protected function deleteItem($id)
    {
        DB::query('delete from catalog_category_cache where category_id = ?', [$id]);
        DB::query('delete from catalog_p2c where category_id = ?', [$id]);
        DB::query('delete from catalog_product_param_cat where category_id = ?', [$id]);
        $ret = parent::deleteItem($id);
        CategoryAssist::generateCountCache();
        return $ret;
    }

    public function boolChange()
    {
        parent::boolChange();
        if ($_REQUEST['field'] == 'is_active') {
            CategoryAssist::generateCountCache();
        }
    }
}