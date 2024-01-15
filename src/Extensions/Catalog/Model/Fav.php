<?php
namespace App\Extensions\Catalog\Model;

use App\Extensions\Catalog\SessionAssist;
use Simflex\Core\Buffer;
use Simflex\Core\DB;
use Simflex\Core\ModelBase;

class Fav extends ModelBase
{
    protected static $table = 'catalog_fav';
    protected static $primaryKeyName = 'fav_id';

    public static function getOrInsert(int $id = 0)
    {
        return Buffer::getOrSet('fav', function () use ($id) {
            $fav = self::findOne(['sess_id' => SessionAssist::getId()]);
            if (!$fav) {
                if (($fav = self::findOne(['user_id' => $id]))) {
                    $fav->sess_id = SessionAssist::getId();
                    $fav->save();
                    $fav->reload();
                    return $fav;
                }

                $fav = new self;
                $id ? ($fav->user_id = $id) : ($fav->sess_id = SessionAssist::getId());
            } else {
                if ($id && ($fav2 = self::findOne(['user_id' => $id]))) {
                    $fav2->sess_id = SessionAssist::getId();
                    $fav2->save();
                    $fav2->reload();
                    return $fav2;
                }

                if ($id && !$fav->user_id) {
                    DB::query('update catalog_fav set user_id = 0 where user_id = ?', [$id]);
                }

                $fav->user_id = $id;
            }

            $fav->save();
            $fav->reload();

            return $fav;
        });
    }

    public function inFav(int $prod): bool
    {
        $in = Buffer::getOrSet('favarr', function () {
            $arr = [];

            $q = DB::query('select product_id from catalog_fav_product where fav_id = ?', [$this->fav_id]);
            while ($r = DB::fetch($q)) {
                $arr[] = (int)$r['product_id'];
            }

            return $arr;
        });

        return in_array($prod, $in);
    }

    public function addProduct(int $id): bool
    {
        return !!DB::query('insert into catalog_fav_product (fav_id, product_id) values (?, ?)', [$this->fav_id, $id]);
    }

    public function removeProduct(int $id): bool
    {
        return !!DB::query('delete from catalog_fav_product where fav_id = ? and product_id = ?', [$this->fav_id, $id]);
    }

    public function getProductCount(): int
    {
        return DB::result('select count(*) from catalog_fav_product where fav_id = ?', 0, [$this->fav_id]);
    }
}