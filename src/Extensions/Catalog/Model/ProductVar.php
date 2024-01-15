<?php
namespace App\Extensions\Catalog\Model;

use Simflex\Core\ModelBase;

class ProductVar extends ModelBase
{
    protected static $primaryKeyName = 'var_id';
    protected static $table = 'catalog_product_var';
}