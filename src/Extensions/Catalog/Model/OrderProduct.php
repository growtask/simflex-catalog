<?php
namespace App\Extensions\Catalog\Model;

use Simflex\Core\ModelBase;

class OrderProduct extends ModelBase
{
    protected static $table = 'catalog_order_product';
    protected static $primaryKeyName = 'order_product_id';
}