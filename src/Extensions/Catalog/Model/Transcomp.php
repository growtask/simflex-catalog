<?php
namespace App\Extensions\Catalog\Model;

use Simflex\Core\ModelBase;

class Transcomp extends ModelBase
{
    protected static $table = 'catalog_transcomp';
    protected static $primaryKeyName = 'transcomp_id';

    public static function getAll()
    {
        return self::findAdv()->orderBy('npp')->all();
    }
}