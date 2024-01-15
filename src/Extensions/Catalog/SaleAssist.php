<?php
namespace App\Extensions\Catalog;

use Simflex\Core\DB;

class SaleAssist
{
    public function up($disc, $cats)
    {
        $disc = 1.0 - max(min($disc, 1.0), 0.0);
        if (!$cats) {
            DB::query("update catalog_product p set bk_enable = 1, bk_price = price, bk_price_old = price_old, price_old = price, price = price * $disc where is_active = 1");
        } else {
            foreach ($cats as $cat) {
                DB::query(
                    "WITH RECURSIVE CategoryHierarchy AS (
    SELECT category_id, pid
    FROM catalog_category
    WHERE category_id in ($cat)

    UNION ALL

    SELECT t.category_id, t.pid
    FROM catalog_category t
             JOIN CategoryHierarchy c ON t.pid = c.category_id
)

update catalog_product p set bk_enable = 1, bk_price = price, bk_price_old = price_old, price_old = price, price = price * $disc where is_active = 1 and COALESCE(bk_enable, 0) = 0 and
    p.product_id in (select p2c.product_id from catalog_p2c p2c inner join CategoryHierarchy ch on ch.category_id = p2c.category_id);"
                );
            }
        }
    }

    public function down()
    {
        DB::query('update catalog_product p set bk_enable = 0, price = bk_price, price_old = bk_price_old where bk_enable = 1');
    }
}