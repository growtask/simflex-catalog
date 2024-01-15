<?php
namespace App\Extensions\Catalog\Admin;

use Simflex\Admin\Base;
use Simflex\Core\DB;

class User extends Base
{
    public function showActions()
    {
        $asset = asset('img/icons/svg-defs.svg');
        echo <<<HTML
        <a href="?action=downloadMailing" target="_blank" class="BtnPrimarySm BtnIconLeft">
        <svg class="notification__close-icon" fill="none" stroke="white" viewBox="0 0 24 24">
            <use xlink:href="$asset#download"></use>
        </svg>
        Скачать рассылку
    </a>
HTML;

        parent::showActions();
    }

    protected function deleteItem($id)
    {
        DB::query('delete from catalog_cart_product where cart_id in (select cart_id from catalog_cart where user_id = ?)', [$id]);
        DB::query('delete from catalog_cart where user_id = ?', [$id]);
        DB::query('delete from catalog_fav_product where fav_id in (select fav_id from catalog_fav where user_id = ?)', [$id]);
        DB::query('delete from catalog_fav where user_id = ?', [$id]);
        DB::query('delete from catalog_order_product where order_id in (select order_id from catalog_order where user_id = ?)', [$id]);
        DB::query('delete from catalog_order where user_id = ?', [$id]);

        return parent::deleteItem($id);
    }

    public function downloadMailing()
    {
        $csv = [
            ['ID', 'Имя', 'Почта']
        ];

        $q = DB::query('select user_id, name, last_name, patronym, email from user where in_mailing = 1 and active = 1 and code = 0');
        while ($r = DB::fetch($q)) {
            $csv[] = [$r['user_id'], implode(' ', [$r['last_name'], $r['name'], $r['patronym']]), $r['email']];
        }

        header('Content-Disposition: attachment;filename=mailing.csv');
        exit(implode("\n", array_map(function ($i) {
            return implode(';', array_map(function ($k) {
                return str_replace([',', ';', "\t", "\n", "\r"], '', $k);
            }, $i));
        }, $csv)));
    }

    public function save()
    {
        $ret = parent::save();
        if (isset($_REQUEST['password']) && $_REQUEST['password'] && $ret > 3) {
            // log out.
            DB::query('delete from user_auth where user_id = ?', [$ret]);
            DB::query('update user set hash = \'\' where user_id = ?', [$ret]);
        }

        return $ret;
    }
}