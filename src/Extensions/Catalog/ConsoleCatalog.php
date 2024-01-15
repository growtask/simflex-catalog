<?php
namespace App\Extensions\Catalog;

use App\Core\Console\Alert;
use Simflex\Core\ConsoleBase;
use Simflex\Core\DB;

class ConsoleCatalog extends ConsoleBase
{
    public function updateFilterCaches()
    {
        Alert::text('Updating all caches');

        $gen = new CacheGen();
        $gen->updateAll();
    }

    public function updateCategoryCounts()
    {
        Alert::text('Updating count cache');
        CategoryAssist::generateCountCache();
    }

    public function updateProductCaches()
    {
        Alert::text('Updating product caches');

        DB::query('delete from catalog_p2p_cache');

        $q = DB::query('select product_id, name from catalog_product where is_active = 1');
        while ($r = DB::fetch($q)) {
            $in = $r['product_id'];

            $q2 = "SELECT
    CASE
        WHEN (p.left_id = t.product_id AND p.right_id IN ($in)) THEN p.right_id
        WHEN (p.right_id = t.product_id AND p.left_id IN ($in)) THEN p.left_id
        ELSE 0
        END AS source_id,
    t.product_id
FROM `catalog_product` t
         INNER JOIN catalog_p2p p ON
        (p.left_id = t.product_id AND p.right_id IN ($in))
        OR (p.right_id = t.product_id AND p.left_id IN ($in))
WHERE t.is_active = 1";

            $q2 = DB::query($q2);
            while ($r2 = DB::fetch($q2)) {
                DB::query('insert into catalog_p2p_cache (source_id, product_id) values (?, ?)', [
                    $r2['source_id'], $r2['product_id']
                ]);
            }

            Alert::success('Updated ' . $r['name']);
        }

        Alert::success('Updated caches');
    }
}