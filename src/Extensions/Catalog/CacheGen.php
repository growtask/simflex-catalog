<?php
namespace App\Extensions\Catalog;

use App\Core\Console\Alert;
use App\Extensions\Catalog\Model\Category;
use Simflex\Core\DB;

class CacheGen
{
    private const PRODUCT_FILTERS = ['is_new', 'is_popular', 'stock'];
    private const PRODUCT_FAKE_FILTERS = ['is_sale', 'size'];

    public function updateAll()
    {
        foreach (Category::findAdv()->all() as $cat) {
            $this->updateFor($cat->category_id);
        }
    }

    public function updateFor(int $cat)
    {
        // drop info
        DB::query('delete from catalog_category_cache where category_id = ?', [$cat]);

        $q = DB::query("WITH RECURSIVE CategoryHierarchy AS (
    SELECT category_id, pid
    FROM catalog_category
    WHERE category_id = ?

    UNION ALL

    SELECT t.category_id, t.pid
    FROM catalog_category t
             JOIN CategoryHierarchy c ON t.pid = c.category_id
)

SELECT DISTINCT
    p.is_new,
    p.stock,
    (p.price <> p.price_old) AS is_sale,
    p.is_popular,
    GROUP_CONCAT(DISTINCT sizes_table.size) AS size
FROM catalog_product p
         JOIN catalog_p2c cc ON p.product_id = cc.product_id
         LEFT JOIN JSON_TABLE(
        CAST(p.size AS json),
        '$.v[*]'
        COLUMNS (
            size VARCHAR(255) PATH '$.size'
            )
    ) AS sizes_table ON 1=1 -- Left join to extract sizes
WHERE p.is_active = 1 and cc.category_id IN (
    SELECT category_id
    FROM CategoryHierarchy
)
GROUP BY p.is_new, p.stock, (p.price <> p.price_old), p.is_popular", [$cat]);

        $filters = [];
        $sizes = [];

        while ($r = DB::fetch($q)) {
            foreach (array_keys($r) as $k) {
                if ($r[$k]) {
                    $filters[] = $k;
                    if ($k == 'size') {
                        $sizes[] = $r[$k];
                    }
                }
            }
        }

        $filters = array_unique($filters);

        $sizes = implode(',', $sizes);
        $sizes = implode(',', array_unique(array_filter(explode(',', $sizes), function ($e) {
            return !!$e;
        })));

        $q = DB::query("WITH RECURSIVE CategoryHierarchy AS (
    SELECT category_id, pid
    FROM catalog_category
    WHERE category_id = ?

    UNION ALL

    SELECT t.category_id, t.pid
    FROM catalog_category t
             JOIN CategoryHierarchy c ON t.pid = c.category_id
)

SELECT DISTINCT
    cpv.param_id,
    cpv.value AS param_value
FROM catalog_product_param_value cpv
         JOIN catalog_p2c cc ON cpv.product_id = cc.product_id
         JOIN catalog_product p ON cpv.product_id = p.product_id
WHERE cc.category_id IN (
    SELECT category_id
    FROM CategoryHierarchy
)
  AND p.is_active = 1", [$cat]);

        $paramArr = [];
        while ($r = DB::fetch($q)) {
            if (!$r['param_value']) {
                continue;
            }

            foreach (explode(',', $r['param_value']) as $v) {
                if (!$v) {
                    continue;
                }

                $paramArr[$r['param_id']][] = $v;
            }
        }

        foreach ($paramArr as $k => $v) {
            $paramArr[$k] = array_values(array_unique($v));
        }

        DB::query('insert into catalog_category_cache (category_id, filters, params, param_values, sizes) values (?, ?, ?, ?, ?)', [
            $cat, implode(',', $filters), implode(',', array_keys($paramArr)), json_encode($paramArr, JSON_UNESCAPED_UNICODE), $sizes
        ]);

        Alert::success('Finished ' . $cat);
    }

    private function testFake(string $name, $row)
    {
        return $this->{'test' . str_replace('_', '', $name)}($row);
    }

    private function testIsSale($row)
    {
        return $row['price_old'] && $row['price'] != $row['price_old'];
    }

    private function testSize($row)
    {
        return !!(json_decode($row['size'], true)['v'] ?? []);
    }
}