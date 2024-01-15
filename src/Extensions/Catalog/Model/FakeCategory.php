<?php

namespace App\Extensions\Catalog\Model;

use App\Extensions\Catalog\CategoryAssist;
use Simflex\Core\Buffer;
use Simflex\Core\DB;
use Simflex\Core\Profiler;

class FakeCategory
{
    public $category_id = 0;
    public $banner_enable = false;
    public $name = 'Каталог';
    public $pid = 0;

    public $seo = '';
    public $seo2 = '';
    public $seo_title = '';
    public $seo_title2 = '';

    public function getProducts($limit = '', $order = '', $filter = '')
    {
        Profiler::traceStart($this, __FUNCTION__);
        $ret = Buffer::getOrSet('fc.all.' . md5($limit . '.' . $order . '.' . $filter), function () use ($filter, $order, $limit) {
            $q = (new DB\AQ())->from('catalog_product', 't')
                ->where(['is_active' => 1])
                ->setModelClass(Product::class);

            if ($filter) {
                $q = $q->andWhere($filter);
            }

            if ($limit) {
                $q = $q->limit($limit);
            }

            if ($order) {
                $q = $q->orderBy($order);
            }

            $all = $q->all();
            CategoryAssist::queryLinks($all);
            return $all;
        });
        Profiler::traceEnd();
        return $ret;
    }

    public function getFilters()
    {
        $ret = ['filters' => [], 'params' => []];

        // this is a huge meme, but let's get a cache record with the longest thing
        $cq = DB::query('select 
    (select filters from catalog_category_cache order by length(filters) desc limit 1) as filters, 
    (select params from catalog_category_cache order by length(params) desc limit 1) as params,
    (select param_values from catalog_category_cache order by length(param_values) desc limit 1) as param_values,
    (select sizes from catalog_category_cache order by length(sizes) desc limit 1) as sizes
    ');

        $cache = DB::fetch($cq);

        // no shot...
        if (!$cache) {
            return $ret;
        }

        // load filters
        $ret['filters'] = explode(',', $cache['filters']);
        $ret['sizes'] = array_filter(explode(',', $cache['sizes']), function ($s) {
            return !strpos($s, '/');
        });

        // load params (active only!)
        foreach (explode(',', $cache['params']) as $p) {
            if ($param = ProductParam::findOne(['param_id' => $p, 'is_active' => 1])) {
                $ret['params'][] = $param;
            }
        }

        $ret['param_values'] = json_decode($cache['param_values'], true);

        // sort params
        usort($ret['sizes'], function ($a, $b) {
            return (int)$a > (int)$b;
        });

        usort($ret['params'], function ($a, $b) {
            return $a->npp > $b->npp;
        });

        return $ret;
    }

    public function getProductCount($filter = '')
    {
        return Buffer::getOrSet('fc.count.' . md5($filter), function () use ($filter) {
            $q = (new DB\AQ())->from('catalog_product', 't')
                ->select('COUNT(*) c')
                ->where(['is_active' => 1]);

            if ($filter) {
                $q = $q->andWhere($filter);
            }

            return $q->fetchScalar();
        });
    }
}