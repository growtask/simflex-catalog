<?php

namespace App\Extensions\Catalog\Admin;

use Simflex\Admin\Base;
use Simflex\Core\DB;

class Analytics extends Base
{
    protected function compareAggrAgainst(array $a, int $b, string $status)
    {
        $against = $this->aggregateByStatus($b, $status);
        $toProc = function ($a, $b, $n) {
            return ((max($a[$n], $b[$n]) - min($a[$n], $b[$n])) / max($a[$n], $b[$n])) * 100;
        };

        return [
            'total' => $toProc($a, $against, 'total'),
            'prods' => $toProc($a, $against, 'prods'),
            'tot' => $toProc($a, $against, 'tot'),
            'act' => $toProc($a, $against, 'act'),
            'marg' => $toProc($a, $against, 'marg'),
        ];
    }

    protected function aggregateByStatus(int $month, string $status)
    {
        // todo: replace by base price
        $q = DB::query('select sum(co.*) as total, sum(co.qty) as prods, sum(co.sum_total) as tot, sum(co.sum_actual) as act, (select tot - act) as marg
                                from catalog_order co where MONTH(`date`) = ? and status = ?', [$month, $status]);
        return DB::fetch($q);
    }

    protected function getTopProducts()
    {
        $q = DB::query('select product_id, name, photo, sum(co.*) as total, sum(co.price) as price 
                               from catalog_product cp 
                               left join catalog_order_product co on (co.product_id = cp.product_id) 
                               order by total desc
                               limit 50');

        return DB::assoc($q);
    }
}