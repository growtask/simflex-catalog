<?php
namespace App\Extensions\Catalog\Component;

use App\Extensions\Catalog\MailAssist;
use App\Extensions\Catalog\SessionAssist;
use App\Extensions\Catalog\Social\Vk;
use App\Extensions\Catalog\Social\Yandex;
use App\Plugins\GeoIp\GeoIp;
use App\Plugins\ReCaptcha\ReCaptcha;
use Simflex\Auth\Bootstrap;
use Simflex\Auth\CookieTokenBag;
use Simflex\Auth\Models\UserAuth;
use Simflex\Auth\SessionStorage;
use Simflex\Core\ComponentBase;
use Simflex\Core\Container;
use Simflex\Core\DB;
use Simflex\Core\Helpers\Str;
use Simflex\Core\Models\User;
use Simflex\Core\Session;

class Auth extends ComponentBase
{
    protected function content()
    {
        $r = Container::getRequest()->getPath();
        if (str_starts_with($r, '/auth/return/')) {
            $this->oauthComplete(Container::getRequest()->getUrlLastPart());
        }

        if (!method_exists($this, $_REQUEST['action'])) {
            exit('{"success":false}');
        }

        exit(json_encode($this->{$_REQUEST['action']}(), JSON_UNESCAPED_UNICODE));
    }

    protected function login()
    {
        // check if user already exists
        if (Container::getUser()) {
            return ['success' => false, 'error' => 'logged_in'];
        }

        $login = DB::escape($_REQUEST['login']);
        $password = DB::escape($_REQUEST['password']);

        // locate user
        $user = User::findOne("login like '$login' or email like '$login'");
        if (!$user || !$user->active) {
            return ['success' => false, 'error' => 'wrong_username_or_password'];
        }

        // check hash
        if (!$this->verifyHash($password, $user->password)) {
            return ['success' => false, 'error' => 'wrong_username_or_password'];
        }

        // finalize
        $this->auth($user);
        return ['success' => true];
    }

    protected function register()
    {
        // check if user already exists
        if (Container::getUser()) {
            return ['success' => false, 'error' => 'logged_in'];
        }

        if ($_REQUEST['user-email'] ?? '') {
            return ['success' => false];
        }

        $name = DB::escape($_REQUEST['name']);
        $email = DB::escape($_REQUEST['email']);
        $password = $_REQUEST['password'];
        $passwordChk = $_REQUEST['password_chk'];
        $phone = DB::escape($_REQUEST['phone'] ?: '');

        // check passwords
        if ($password != $passwordChk) {
            return ['success' => false, 'error' => 'passwords_dont_match'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'error' => 'too_small_password'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'invalid_email'];
        }

        // locate user
        $user = User::findOne("login like '$email' or email like '$email'");
        if ($user) {
            return ['success' => false, 'error' => 'email_already_taken'];
        }

        // create user
        $user = new User();
        $user->role_id = 3;
        $user->active = 1;
        $user->login = $email;
        $user->password = $this->makeHash($password);
        $user->email = $email;
        $user->name = $name;
        $user->hash = '';
        $user->hash_admin = '';
        $user->code = random_int(100000, 999999); // verify code
        $user->phone = $phone;
        $user->city = GeoIp::getCurrentCity();

        if (!$user->save()) {
            return ['success' => false, 'error' => 'system_error', 'extra' => DB::error()];
        }

        $user->reload();

        // auth newly created
        $this->auth($user);

        // send verification email (only in live mode)
        (new MailAssist($email, 'Подтверждение аккаунта Непоседа'))
            ->tpl('email_registration_complete.tpl', ['user' => $user])
            ->send();

        return ['success' => true];
    }

    protected function complete()
    {
        $u = User::findOne(['user_id' => $_REQUEST['u']]);
        if (!$u || !$u->code || $u->code != $_REQUEST['c']) {
            header('Location: /');
            exit;
        }

        $u->code = 0;
        $u->save();

        Session::set('email_confirmed', true);
        header('Location: /user/');
        exit;
    }

    protected function resendMail()
    {
        if (!Container::getUser()) {
            return ['success' => false];
        }

        $u = Container::getUser();
        if (!$u->code) {
            return ['success' => false];
        }

        (new MailAssist($u->email, 'Подтверждение аккаунта Непоседа'))
            ->tpl('email_registration_complete.tpl', ['user' => $u])
            ->send();

        return ['success' => true];
    }

    protected function logout()
    {
        if (!Container::getUser()) {
            header('Location: /');
            exit;
        }

        SessionStorage::set(0);
        Bootstrap::signOut();

        header('Location: /');
        exit;
    }

    protected function reset()
    {
        // check if user already exists
        if (Container::getUser()) {
            return ['success' => false, 'error' => 'logged_in'];
        }

        if (!ReCaptcha::checkResponse()) {
            return ['success' => false];
        }

        $email = DB::escape($_REQUEST['email']);

        // locate user
        $user = User::findOne("login like '$email' or email like '$email'");
        if (!$user || !$user->active) {
            return ['success' => false, 'error' => 'user_not_found'];
        }

        $pw = Str::random(16);
        DB::query('update user set password = ? where user_id = ?', [$this->makeHash($pw), $user->user_id]);

        // send reset email (only in live mode)
        (new MailAssist($email, 'Сброс пароля Непоседа'))
            ->tpl('email_password_recovery.tpl', ['user' => $user, 'password' => $pw])
            ->send();

        return ['success' => true];
    }

    protected function oauth()
    {
        $sb = null;
        switch ($_REQUEST['id']) {
            case 'vk':
                $sb = new Vk();
                break;
            case 'yandex':
                $sb = new Yandex();
                break;
        }

        if (!$sb) {
            header('Location: /');
            exit;
        }

        $sb->beginOAuth();
    }

    protected function detach()
    {
        if (!Container::getUser()) {
            header('Location: /');
            exit;
        }

        $u = Container::getUser();
        $id = DB::escape($_REQUEST['id']);
        DB::query("update user set {$id}_active = 0, {$id}_id = '' where user_id = ?", [$u->user_id]);
        $u->reload();
        header('Location: /user/');
        exit;
    }

    protected function oauthComplete(string $id)
    {
        $sb = null;
        switch ($id) {
            case 'vk':
                $sb = new Vk();
                break;
            case 'yandex':
                $sb = new Yandex();
                break;
        }

        if (!$sb) {
            var_dump('no sb');
            // header('Location: /');
            exit;
        }

        $id = DB::escape($id);

        $data = $sb->handleResponse();
        if (!$data['id']) {
            var_dump('no id');
            // header('Location: /');
            exit;
        }

        if (Session::get('oauth_admin_upload') ?? false) {
            Session::set('oauth_data', $data);
            header('Location: /admin/shop/product/product/?action=vkUploadAuth');
            exit;
        }

        $u = Container::getUser();
        if (!$u) {
            // find acc
            $u = User::findOne("{$id}_id like '{$data['id']};;%' or email like '{$data['email']}'");
            if (!$u) {
                // create user
                $u = new User();
                $u->role_id = 3;
                $u->active = 1;
                $u->login = $data['email'];
                $u->password = '';
                $u->email = $data['email'];
                $u->name = $data['first_name'];
                $u->last_name = $data['last_name'];
                $u->hash = '';
                $u->hash_admin = '';
                $u->code = 0;
                $u->phone = '';
                $u->city = GeoIp::getCurrentCity();
                $u->{$id . '_id'} = implode(';;', [$data['id'], $data['name']]);
                $u->{$id . '_active'} = 1;

                if (!$u->save()) {
                    var_dump('cant save reg');
                    // header('Location: /');
                    exit;
                }

                $u->reload();

                // auth newly created
                $this->auth($u);
                header('Location: /user/');
                exit;
            }
        }

        // enable shit
        $r = DB::query("update user set {$id}_id = ?, {$id}_active = 1 where user_id = ?", [implode(';;', [$data['id'], $data['name']]), $u->user_id]);
        if (!$r) {
            var_dump('cant save normal', DB::error());
            // header('Location: /');
            exit;
        }

        $u->reload();

        // login
        $this->auth($u);
        header('Location: /user/');
        exit;
    }

    protected function auth($user)
    {
        // perform login
        SessionStorage::set($user->user_id);
        Bootstrap::authByUser($user);

        // remember
        $auth = UserAuth::create($user->user_id);
        $cookies = new CookieTokenBag(CookieTokenBag::defaultPrefix());
        $cookies->set($auth->token, new \DateTime('1 WEEK'));
    }

    protected function verifyHash(string $in, string $hash): bool
    {
        // super advanced hashing done by superhuman 1C developers
        // they also claim their cms is "the best", this meme perfectly shows their competence
        if (strlen($hash) == 40) {
            $salt = substr($hash, 0, 8);
            return ($salt . md5($salt . $in)) == $hash;
        }

        // nice programming 1C
        $digestMeta = explode('$', $hash);
        if (!($digestMeta[2] ?? null)) {
            return false;
        }

        return hash_equals($hash, $this->makeHash($in, $digestMeta[2]));
    }

    protected function makeHash(string $in, string $salt = ''): string
    {
        return crypt($in, '$6$' . ($salt ?: Str::random(16)) . '$');
    }
}