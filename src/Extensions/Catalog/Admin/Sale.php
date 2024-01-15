<?php

namespace App\Extensions\Catalog\Admin;

use App\Extensions\Catalog\SaleAssist;
use Simflex\Admin\Base;
use Simflex\Admin\Plugins\Alert\Alert;
use Simflex\Core\DB;
use Simflex\Core\Time;

class Sale extends Base
{
    public function save()
    {
        if ($_REQUEST['is_active']) {
            if ($id = DB::result(
                'select sale_id from catalog_sale where end > current_time() and is_running = 1 and sale_id != ?',
                0,
                [$_REQUEST['sale_id'] ?? 0]
            )) {
                Alert::error('Нельзя запустить 2 акции одновременно');
                header('Location: ./?action=show');
                exit;
            }
        }

        $oldData = DB::assoc('select * from catalog_sale where sale_id = ?', false, false, [$_REQUEST['sale_id'] ?? 0])[0] ?? [];
        $sale = new SaleAssist();

        $curActiveStartTime = DB::result('select end from catalog_sale where is_running = 1', 0);
        $isNew = !isset($_REQUEST['sale_id']) || !$_REQUEST['sale_id'];
        if (!$_REQUEST['is_active'] && $isNew && Time::unix($_REQUEST['start']) < Time::unix($curActiveStartTime)) {
            Alert::error('Акция запустится, когда предыдущая акция еще не закончилась');
            header('Location: ./?action=show');
            exit;
        }

        $ret = parent::save();
        if ($ret) {
            $cats = explode(',', $_POST['s2c']);
            if ($isNew || !$oldData) {
                // force insert into categories
                foreach ($cats as $c) {
                    DB::query('insert into catalog_sale_category (category_id, sale_id) values (?, ?)', [$c, $ret]);
                }

                if (Time::unix($_POST['start']) < time() + 60 && $_REQUEST['is_active']) {
                    $sale->up($_POST['discount'] / 100.0, $cats);
                    DB::query('update catalog_sale set is_running = 1 where sale_id = ?', [$ret]);
                }
            } else {
                $newData = DB::assoc('select * from catalog_sale where sale_id = ?', false, false, [$ret])[0] ?? [];
                if ($newData['is_active'] != $oldData['is_active']) {
                    $update = [];
                    if ($newData['is_active']) {
                        if (Time::unix($newData['start']) > time()) {
                            $update['start'] = Time::mysql(time());
                        }

                        $update['is_running'] = 1;
                        $sale->up($newData['discount'] / 100.0, $cats);
                    } else {
                        $sale->down();
                        $update['is_running'] = 0;
                    }

                    DB::query(
                        'update catalog_sale set ' . implode(
                            ', ',
                            array_map(function ($c) {
                                return $c . ' = ?';
                            }, array_keys($update))
                        ) . ' where sale_id = ' . (int)$ret,
                        array_values($update)
                    );
                }
            }
        }

        return $ret;
    }

    protected function deleteItem($id)
    {
        if (DB::result('select is_running from catalog_sale where sale_id = ?', 0, [$id])) {
            $sale = new SaleAssist();
            $sale->down();
        }

        DB::query('delete from catalog_sale_category where sale_id = ?', [$id]);
        return parent::deleteItem($id);
    }
}