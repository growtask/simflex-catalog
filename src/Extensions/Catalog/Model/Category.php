<?php

namespace App\Extensions\Catalog\Model;

use App\Extensions\Catalog\CacheGen;
use App\Extensions\Catalog\CategoryAssist;
use Simflex\Core\Buffer;
use Simflex\Core\DB;
use Simflex\Core\ModelBase;
use Simflex\Core\Profiler;

class Category extends ModelBase
{
    protected static $table = 'catalog_category';
    protected static $primaryKeyName = 'category_id';

    public static function find($where, $orderBy = null, $limit = null, $assocKey = false)
    {
        $id = md5((is_string($where) ? $where : implode(';', array_map(function ($k, $v) {
            return $k . '.' . $v;
        }, array_keys($where), array_values($where)))) . '.' . ($orderBy ?? '') . '.' . ($limit ?? '') . '.' . ($assocKey ? '1' : '0'));

        return Buffer::getOrSet('cat.find.' . $id, function() use ($where, $orderBy, $limit, $assocKey) {
            return parent::find($where, $orderBy, $limit, $assocKey);
        });
    }

    /**
     * @return Product[]
     */
    public function getProducts($limit = '', $order = '', $filter = '')
    {
        Profiler::traceStart($this, __FUNCTION__);
        $ret = Buffer::getOrSet(
            'cat.' . $this->id . '.products.' . md5($limit . '.' . $order . '.' . $filter),
            function () use ($limit, $order, $filter) {
                $cats = $this->getAllChildren();
                $cats[] = $this->category_id;
                $in = implode(',', $cats);
                $q = (new DB\AQ())->from('catalog_product', 't')
                    ->select('distinct t.*')
                    ->setModelClass(Product::class);

                $qw = "INNER JOIN catalog_p2c cpc ON t.product_id = cpc.product_id
WHERE t.is_active = 1
  AND cpc.category_id IN ($in)";

                if ($filter) {
                    $qw .= ' AND ' . $filter . ' ';
                }

                if ($order) {
                    $qw .= ' ORDER BY ' . $order . ' ';
                }

                if ($limit) {
                    $qw .= ' LIMIT ' . $limit . ' ';
                }

                $q->custom($qw);

                $all = $q->all();
                if (DB::error()) {
                    var_dump(DB::error());
                }
                CategoryAssist::queryLinks($all);
                return $all;
            }
        );
        Profiler::traceEnd($this, __FUNCTION__);
        return $ret;
    }

    public function getParent()
    {
        return Category::findOne(['category_id' => $this->pid]);
    }

    public function getChildren()
    {
        Profiler::traceStart($this, __FUNCTION__);
        $tree = Buffer::getOrSet('cat.tree', function () {
            $tree = [];

            foreach (Category::findAdv()->where('is_active = 1')->all() as $c) {
                $tree[(int)$c->pid][] = $c;
            }

            return $tree;
        });
        Profiler::traceEnd($this, __FUNCTION__);

        return $tree[(int)$this->category_id] ?? [];
    }

    public function getFilters()
    {
        return Buffer::getOrSet('cat.' . $this->id . '.filters', function () {
            $ret = ['filters' => [], 'params' => []];

            // get cached info
            $cq = DB::query(
                'select filters, params, param_values, sizes from catalog_category_cache where category_id = ?',
                [$this->category_id]
            );
            $cache = DB::fetch($cq);

            if (!$cache) {
                // ugh... this is generally a terrible idea but how about we reset cache here anyway?
                $gen = new CacheGen();
                $gen->updateFor($this->category_id);

                $cq = DB::query(
                    'select filters, params, param_values, sizes from catalog_category_cache where category_id = ?',
                    [$this->category_id]
                );
                $cache = DB::fetch($cq);

                if (!$cache) {
                    // still nothing? rip
                    return $ret;
                }
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
        });
    }

    public function getProductCount($filter = '')
    {
        Profiler::traceStart($this, __FUNCTION__);
        if (!$filter) {
            $ret = CategoryAssist::$categoryCounts[$this->category_id] ?? 0;
            Profiler::traceEnd();
            return $ret;
        }

        $ret = Buffer::getOrSet('cat.' . $this->id . '.count.' . md5($filter), function () use ($filter) {
            $cats = $this->getAllChildren();
            $cats[] = $this->category_id;
            $in = implode(',', array_filter($cats, function ($c) { return !!$c; }));

            $qw = "INNER JOIN catalog_p2c cpc ON t.product_id = cpc.product_id
WHERE t.is_active = 1
  AND cpc.category_id IN ($in)";

            if ($filter) {
                $qw .= ' AND ' . $filter;
            }

            $q = (new DB\AQ())->from('catalog_product', 't')
                ->select('COUNT(DISTINCT t.product_id) c')
                ->custom($qw);

            return $q->fetchScalar();
        });
        Profiler::traceEnd($this, __FUNCTION__);
        return $ret;
    }

    public function getAllChildrenInternal()
    {
        Profiler::traceStart($this, __FUNCTION__);
        $ret = Buffer::getOrSet('cat.allchildren.' . $this->category_id, function () {
            $children = [];
            foreach ($this->getChildren() as $c) {
                $children[] = $c;
                foreach ($c->getAllChildrenInternal() as $cc) {
                    $children[] = $cc;
                }
            }

            return $children;
        });

        Profiler::traceEnd($this, __FUNCTION__);
        return $ret;
    }

    public function getAllChildren()
    {
        return Buffer::getOrSet('cat.allchildrenreal.' . $this->category_id, function () {
            return array_unique(array_map(function ($c) {
                return $c->category_id;
            }, $this->getAllChildrenInternal()));
        });
    }
}