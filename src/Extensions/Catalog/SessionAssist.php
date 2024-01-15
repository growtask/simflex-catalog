<?php
namespace App\Extensions\Catalog;

use Simflex\Core\Helpers\Str;
use Simflex\Core\Session;

class SessionAssist
{
    /** @var null|\App\Extensions\Catalog\Model\Cart  */
    public static $cart = null;

    /** @var null|\App\Extensions\Catalog\Model\Fav  */
    public static $fav = null;

    public static function getId(): string
    {
        if (!Session::get('ecom_sess_id')) {
            Session::set('ecom_sess_id', Str::random(32));
        }

        return Session::get('ecom_sess_id');
    }
}