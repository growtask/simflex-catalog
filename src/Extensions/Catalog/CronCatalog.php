<?php

namespace App\Extensions\Catalog;

use Simflex\Core\DB;
use Simflex\Core\Time;

class CronCatalog
{
    public static function doSales()
    {
        $sale = new SaleAssist();

        $q = DB::query('select * from catalog_sale where is_running = 1');
        if ($r = DB::fetch($q)) {
            if (Time::unix($r['end']) <= time()) {
                $sale->down();
                DB::query('update catalog_sale set is_running = 0 where sale_id = ?', [$r['sale_id']]);
                goto STARTNEW;
            }

            return;
        }

        STARTNEW:
        $q = DB::query(
            'select * from catalog_sale where is_running = 0 and start < current_time() and end > current_time()'
        );
        if ($r = DB::fetch($q)) {
            $cats = [];

            $q = DB::query('select category_id from catalog_sale_category where sale_id = ?', [$r['sale_id']]);
            while ($r1 = DB::fetch($q)) {
                $cats[] = $r1['category_id'];
            }

            $sale->up($r['discount'] / 100.0, $cats);
            DB::query('update catalog_sale set is_running = 1 where sale_id = ?', [$r['sale_id']]);
        }
    }
}