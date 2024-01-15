<?php
namespace App\Extensions\Catalog\Component;

use App\Extensions\Catalog\SessionAssist;
use Simflex\Core\Container;
use Simflex\Core\DB;
use Simflex\Core\Helpers\Str;
use Simflex\Core\Session;
use Simflex\Extensions\Content\Content;

class User extends Content
{
    protected function content()
    {
        if (!Container::getUser()) {
            Session::set('ask_login', true);
            header('Location: /');
            exit;
        }

        if (Container::getRequest()->isPost()) {
            $this->onSave();
        }

        parent::content();
    }

    protected function onSave()
    {
        $r = Container::getRequest();
        if (!$r->request('name')) {
            return;
        }

        $u = Container::getUser();

        $update = [];
        if ($r->request('password') && $r->request('password') == $r->request('confirm_password')) {
            $update[] = ['password', DB::wrapString(crypt($r->request('password'), '$6$' . Str::random(16) . '$'))];
        }

        if ($r->request('email') != $u->email) {
            $update[] = ['code', random_int(100000, 999999)];
        }

        $update[] = ['name', DB::wrapString($r->request('name'))];
        $update[] = ['last_name', DB::wrapString($r->request('last_name'))];
        $update[] = ['patronym', DB::wrapString($r->request('patronym'))];
        $update[] = ['phone', DB::wrapString($r->request('phone'))];
        $update[] = ['email', DB::wrapString($r->request('email'))];

        $update[] = ['org_active', $r->request('org_active') == 'on' ? 1 : 0];
        if ($r->request('org_active')) {
            $update[] = ['org_name', DB::wrapString($r->request('org_name'))];
            $update[] = ['org_inn', DB::wrapString($r->request('org_inn'))];
        }

        $update[] = ['other_active', $r->request('other_active') == 'on' ? 1 : 0];
        if ($r->request('other_active') == 'on') {
            $update[] = ['other_name', DB::wrapString($r->request('other_name'))];
            $update[] = ['other_last_name', DB::wrapString($r->request('other_last_name'))];
            $update[] = ['other_patronym', DB::wrapString($r->request('other_patronym'))];
            $update[] = ['other_phone', DB::wrapString($r->request('other_phone'))];
        }

        $update[] = ['in_mailing', $r->request('in_mailing') == 'on' ? 1 : 0];
        $update[] = ['city', DB::wrapString($r->request('city'))];
        $update[] = ['transcomp', DB::wrapString($r->request('transcomp'))];
        $update[] = ['address', DB::wrapString($r->request('address'))];

        DB::query('update user set ' . implode(',', array_map(function ($i) { return implode('=', $i); }, $update)) . ' where user_id = ?', [$u->user_id]);

        Session::set('user_updated', true);
        $u->reload();
    }
}