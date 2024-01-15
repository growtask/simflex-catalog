<?php
namespace App\Extensions\Catalog\Model;

use Simflex\Core\DB;
use Simflex\Core\ModelBase;

class ProductParam extends ModelBase
{
    protected static $table = 'catalog_product_param';
    protected static $primaryKeyName = 'param_id';

    public function inCategory(int $cat)
    {
        return (new DB\AQ())->from('catalog_product_param_cat')->where([
            'param_id' => $this->id,
            'category_id' => $cat
        ])->select('count(*)')->fetchScalar() > 0;
    }

    public function hasAnyCategory()
    {
        return (new DB\AQ())->from('catalog_product_param_cat')->where([
                'param_id' => $this->id,
            ])->select('count(*)')->fetchScalar() > 0;
    }

    public function getValues()
    {
        $values = [];

        $q = DB::query('select distinct value from catalog_product_param_value where param_id = ?', [$this->param_id]);
        while ($r = DB::fetch($q)) {
            if (!$r['value']) {
                continue;
            }

            foreach (explode(',', $r['value']) as $vv) {
                $values[] = $vv;
            }
        }

        return array_unique($values);
    }
}