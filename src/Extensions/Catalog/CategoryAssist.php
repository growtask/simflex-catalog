<?php
namespace App\Extensions\Catalog;

use App\Extensions\Catalog\Model\Product;
use Simflex\Core\DB;
use Simflex\Core\Profiler;

class CategoryAssist
{
    public static function fixPath(string $p): string
    {
        if (isset($_REQUEST['q'])) {
            return str_replace('catalog/', 'search/', $p);
        }

        if (isset($_REQUEST['wl'])) {
            return str_replace('catalog/', 'favorite/', $p);
        }

        return $p;
    }

    public static function maintainQuery(): string
    {
        $out = [];
        if (isset($_REQUEST['q'])) {
            $out['q'] = $_REQUEST['q'];
        }
        if (isset($_REQUEST['wl'])) {
            $out['wl'] = $_REQUEST['wl'];
        }

        return http_build_query($out);
    }

    public static $linkPaths = [];

    public static function queryLinks($ids)
    {
        Profiler::traceStart(__CLASS__, __FUNCTION__);
        $list = [];
        foreach ($ids as $id) {
            if ($id instanceof Product) {
                $list[] = $id->product_id;
            } else {
                $list[] = $id;
            }
        }

        // eliminate shit
        $list = array_diff($list, array_keys(self::$linkPaths));
        if (!$list) {
            Profiler::traceEnd();
            return;
        }

        // compose in
        $in = implode(',', $list);

        // query shit
        $q = "SELECT distinct cp.*, c.source_id
FROM `catalog_p2p_cache` c
INNER JOIN `catalog_product` cp ON cp.product_id = c.product_id
WHERE c.source_id in ($in)";

        $q = DB::query($q);
        while ($r = DB::fetch($q)) {
            $id = $r['source_id'];
            unset($r['source_id']);

            $p = new Product();
            $p->fill($r);

            if (!isset(self::$linkPaths[$id])) {
                self::$linkPaths[$id] = [];
            }

            // uff
            self::$linkPaths[$id][] = $p;
        }

        Profiler::traceEnd();
    }

    public static $categoryCounts = [];

    public static function loadCounts()
    {
        foreach ((new DB\AQ())->from('catalog_category_count')->asArray()->all() as $d) {
            self::$categoryCounts[$d['category_id']] = $d['count'];
        }
    }

    public static function generateCountCache()
    {
        DB::query('DELETE FROM catalog_category_count');
        DB::query("INSERT INTO catalog_category_count (category_id, count)
WITH RECURSIVE CategoryHierarchy AS (
    SELECT category_id, pid, category_id AS root_category_id
    FROM catalog_category
    WHERE is_active = 1

    UNION ALL

    SELECT cc.category_id, cc.pid, ch.root_category_id
    FROM catalog_category cc
             INNER JOIN CategoryHierarchy ch ON cc.pid = ch.category_id
),
               CategoryProductCounts AS (
                   SELECT ch.root_category_id AS category_id, cp.product_id
                   FROM CategoryHierarchy ch
                            INNER JOIN catalog_p2c cp ON ch.category_id = cp.category_id
                            INNER JOIN catalog_product p ON cp.product_id = p.product_id
                   WHERE p.is_active = 1
               )
SELECT
    cpc.category_id,
    COUNT(DISTINCT cpc.product_id) AS count
FROM CategoryProductCounts cpc
GROUP BY cpc.category_id");
    }
}