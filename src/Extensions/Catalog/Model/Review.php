<?php
namespace App\Extensions\Catalog\Model;

use Simflex\Core\DB;
use Simflex\Core\ModelBase;

class Review extends ModelBase
{
    protected static $table = 'catalog_review';
    protected static $primaryKeyName = 'review_id';

    public function getImages()
    {
        $out = [];

        $q = DB::query('select path from catalog_upload where upload_id in (select upload_id from catalog_review_image where review_id = ?)', [$this->review_id]);
        while ($r = DB::fetch($q)) {
            $out[] = $r['path'];
        }

        return $out;
    }
}