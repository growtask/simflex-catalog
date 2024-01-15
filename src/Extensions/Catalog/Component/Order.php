<?php
namespace App\Extensions\Catalog\Component;

use App\Extensions\Catalog\MailAssist;
use App\Extensions\Catalog\Model\OrderProduct;
use App\Extensions\Catalog\SessionAssist;
use Simflex\Core\Container;
use Simflex\Core\Core;
use Simflex\Core\DB;
use Simflex\Core\Session;
use Simflex\Core\Time;
use Simflex\Extensions\Content\Content;
use Simflex\Extensions\Content\Model\ModelContent;

class Order extends Content
{
    protected $template = '';
    protected $title = '';
    protected $path = '/user/order/';

    /** @var null|\App\Extensions\Catalog\Model\Order */
    public $order = null;
    public $orders = [];
    public $totalOrders = 0;
    public $allYears = [];

    public function get($path = ''): ?ModelContent
    {
        if ($content = ModelContent::findOne(['path' => $this->path, 'active' => 1])) {
            $content['params'] = unserialize($content['params']);
        }

        if ($this->template) {
            $content->template_path = $this->template;
            $content['title'] = $this->title;
        }

        return $content;
    }

    protected function content()
    {
        $req = Container::getRequest();
        if (str_starts_with($req->getPath(), '/user/orders')) {
            if ($req->getUrlLastPart() != 'orders') {
                $this->item();
            } else {
                $this->all();
            }
        } else {
            if (($a = $_REQUEST['action']) && method_exists($this, $a)) {
                $this->$a();
            }

            if (($uripart = $req->getUrlLastPart()) != 'order' && method_exists(
                    $this,
                    $uripart
                ) && (!isset($a) || $a != $uripart)) {
                $this->{$uripart}();
            }
        }

        parent::content();
    }

    protected function success()
    {
        $this->template = 'order_success.tpl';
        $this->title = 'Заказ оформлен';
    }

    protected function current()
    {
        if (!Container::getUser()) {
            Session::set('ask_login', true);
            header('Location: /');
            exit;
        }

        $lastOrder = \App\Extensions\Catalog\Model\Order::findAdv()->where([
            'user_id' => Container::getUser()->user_id,
        ])->andWhere('status != \'finished\' and status != \'canceled\'')
        ->orderBy('date desc')
        ->limit(1)
        ->fetchOne();

        if (!$lastOrder) {
            header('Location: /user/orders/');
            exit;
        }

        $this->order = $lastOrder;

        $this->template = 'current_order.tpl';
        $this->title = 'Заказ №' . $lastOrder->order_id;
    }

    protected function all()
    {
        $this->path = '/user/orders/';
        $r = Container::getRequest();
        $u = Container::getUser();

        if (!$u) {
            Session::set('ask_login', true);
            header('Location: /');
            exit;
        }

        $q = \App\Extensions\Catalog\Model\Order::findAdv()
            ->limit('10 offset ' . (int)$r->request('page', 0) * 10)
            ->where(['user_id' => $u->user_id]);

        if ($sort = $r->request('sort', 'order_id')) {
            if ($sort == 'sum_actual_asc') {
                $sort = 'sum_actual ASC';
            } elseif ($sort == 'status_num') {
                $sort = 'status_num ASC';
            } else {
                $sort = DB::escape($sort) . ' DESC';
            }

            $q = $q->orderBy($sort);
        }

        $t = \App\Extensions\Catalog\Model\Order::findAdv()->where(['user_id' => $u->user_id]);

        // filter
        if ($y = $r->request('year')) {
            $q = $q->andWhere('YEAR(date) = ' . (int)$y);
            $t = $t->andWhere('YEAR(date) = ' . (int)$y);
        }

        // get all years
        $y = \App\Extensions\Catalog\Model\Order::findAdv()
            ->select('DISTINCT YEAR(date) year')
            ->where(['user_id' => $u->user_id])
            ->asArray()
            ->all();

        $this->allYears = array_map(function ($i) { return $i['year']; }, $y);
        $this->totalOrders = $t->select('count(*)')->fetchScalar() ?? 0;
        $this->orders = $q->setModelClass(\App\Extensions\Catalog\Model\Order::class)->all();

        usort($this->allYears, function ($a, $b) {
            return (int)$a < (int)$b;
        });
    }

    protected function item()
    {
        $r = Container::getRequest();
        $u = Container::getUser();

        if (!$u) {
            Session::set('ask_login', true);
            header('Location: /');
            exit;
        }

        $this->order = \App\Extensions\Catalog\Model\Order::findOne(['order_id' => $r->getUrlLastPart()]);
        if (!$this->order || $this->order->user_id != $u->user_id) {
            header('Location: /user/orders/');
            exit;
        }

        $this->template = 'order.tpl';
        $this->title = 'Заказ №' . $this->order->order_id;
    }

    protected function requestBill()
    {
        $user = Container::getUser();

        if (!$user) {
            Session::set('ask_login', true);
            header('Location: /');
            exit;
        }

        $orderId = $_REQUEST['order_id'];
        $order = \App\Extensions\Catalog\Model\Order::findOne(['order_id' => $orderId]);

        if (!$order) {
            header('Location: /user/orders/');
            exit;
        }

        (new MailAssist(Core::siteParam('form_email'), 'Клиент запросил счет'))
            ->content('Клиент ' . $user->name . ' (.'.$user->email.'.) запросил счёт к заказу ' . $orderId)
            ->send();

        $this->sendTelegram($user->name . ' ('.$user->email.')', $orderId, $order->getTotal(), 'КЛИЕНТ ЗАПРОСИЛ СЧЕТ');
    }

    protected function cloneOrder()
    {
        if (!Container::getUser()) {
            Session::set('ask_login', true);
            header('Location: /');
            exit;
        }

        $order = \App\Extensions\Catalog\Model\Order::findOne(['order_id' => $_REQUEST['order_id']]);
        if (!$order) {
            header('Location: /user/orders/');
            exit;
        }

        // reset card
        $cart = SessionAssist::$cart;
        DB::query('delete from catalog_cart_product where cart_id = ?', [$cart->cart_id]);

        // add products
        foreach ($order->getProducts('', '', 'COALESCE(is_deleted, 0) = 0') as $pi) {
            $p = $pi['product'];
            $cart->addOrUpdateProduct($p->product_id, $pi['size'], max($pi['qty'], 0), true, true);
        }

        $cart->rebuildData();

        header('Location: /cart/');
        exit;
    }

    protected function add()
    {
        if (!Container::getUser()) {
            Session::set('ask_login', true);
            header('Location: /');
            exit;
        }

        $user = Container::getUser();
        $r = Container::getRequest();
        $cart = SessionAssist::$cart;

        $order = \App\Extensions\Catalog\Model\Order::findAdv()
            ->where("user_id = {$user->user_id} and status in ('new', 'accepted', 'validating', 'ready', 'added')")
            ->orderBy('order_id desc')
            ->fetchOne();

        $this->make($order ? 'added' : '');
    }

    protected function make($s = '')
    {
        if (!Container::getUser()) {
            Session::set('ask_login', true);
            header('Location: /');
            exit;
        }

        $user = Container::getUser();
        $r = Container::getRequest();
        $cart = SessionAssist::$cart;

        // make sure cart is not empty
        $cart->rebuildData();
        if (!$cart->getProductCount()) {
            header('Location: /cart/');
            exit;
        }

        // check if we need to update user data
        if ($r->post('change_data') == 'on') {
            DB::query('update user set name = ?, last_name = ?, patronym = ?, phone = ?, org_active = ?, org_name = ?, org_inn = ?, other_active = ?, other_name = ?, other_last_name = ?, other_patronym = ?, other_phone = ?, city = ?, address = ?, transcomp = ? where user_id = ?', [
                $r->post('name'),
                $r->post('last_name'),
                $r->post('patronym'),
                $r->post('phone'),
                $r->post('org_active') == 'on' ? 1 : 0,
                $r->post('org_name'),
                $r->post('org_inn'),
                $r->post('other_active') == 'on' ? 1 : 0,
                $r->post('other_name'),
                $r->post('other_last_name'),
                $r->post('other_patronym'),
                $r->post('other_phone'),
                $r->post('city'),
                $r->post('address'),
                $r->post('transcomp'),
                $user->user_id
            ]);

            $user->reload();
        }

        $otherActive = $r->post('other_active') == 'on';

        // create a new order
        $order = new \App\Extensions\Catalog\Model\Order();
        $order->user_id = $user->user_id;
        $order->date = Time::create()->asMySQL();
        $order->status = $s ?: 'new';
        $order->status_num = $s ? 3 : 0;
        $order->name = $r->post($otherActive ? 'other_name' : 'name');
        $order->last_name = $r->post($otherActive ? 'other_last_name' : 'last_name');
        $order->patronym = $r->post($otherActive ? 'other_patronym' : 'patronym');
        $order->phone = $r->post($otherActive ? 'other_phone' : 'phone');
        $order->user_name = $r->post('name') ?? $user->name;
        $order->user_last_name = $r->post('last_name') ?? $user->last_name;
        $order->user_patronym = $r->post('patronym') ?? $user->patronym;
        $order->user_phone = $r->post('phone') ?? $user->phone;
        $order->email = $user->email;
        $order->transcomp = $r->post('transcomp');
        $order->city = $r->post('city');
        $order->address = $r->post('address');
        $order->comment = $r->post('comment');
        $order->tracking = '';
        $order->sum_total = 0;
        $order->sum_actual = 0;
        $order->qty = 0;
        $order->discount = $user->discount ?? 0;
        $order->org_active = $r->post('org_active') == 'on';
        $order->org_name = $r->post('org_name');
        $order->org_inn = $r->post('org_inn');
        $order->save();
        $order->reload();

        // copy products from cart
        foreach ($cart->getProducts()['items'] as $pi) {
            $p = $pi['product'];
            if (!$pi['qty'] || !$p->is_active || !$p->stock) {
                $cart->removeProduct($p->product_id, $pi['size'], false);
                continue;
            }

            $sizes = $p->getSizesRaw(); $sizeTestPassed = false;
            foreach ($sizes as $sz) {
                if ($sz['size'] == $pi['size'] && !$sz['stock']) {
                    $cart->removeProduct($p->product_id, $pi['size'], false);
                    continue 2;
                }

                if ($sz['size'] == $pi['size']) {
                    $sizeTestPassed = true;
                }
            }

            if (!$sizeTestPassed) {
                $cart->removeProduct($p->product_id, $pi['size'], false);
                continue;
            }

            $op = new OrderProduct();
            $op->order_id = $order->order_id;
            $op->product_id = $p->product_id;
            $op->qty = max($pi['qty'], 0);
            $op->sum = $pi['sum_actual'];
            $op->size = $pi['size'];
            $op->price = $p->__price;
            $op->price_old = $p->__price_old;

            if (!$op->qty) {
                continue;
            }

            // decrease product stock
//            $data = json_decode($p->size, true) ?? [];
//            if ($data) {
//                $decreasedSize = false;
//                foreach ($data['v'] as &$sz) {
//                    if ($sz['size'] == $pi['size']) {
//                        $pi['qty'] = max(min($pi['qty'], $sz['stock']), 0);
//                        if (!$pi['qty']) {
//                            continue 2;
//                        }
//
//                        $op->stock = $sz['stock'];
//                        $sz['stock'] -= $pi['qty'];
//                        if (!$sz['stock']) {
//                            $this->sendTelegramOOS($p->product_id, $p->name, $pi['size']);
//                        }
//
//                        $decreasedSize = true;
//                    }
//                }
//
//                // fails to find the size
//                if (!$decreasedSize) {
//                    $cart->removeProduct($p->product_id, $pi['size'], false);
//                    continue;
//                }
//            }

            if (!$op->save()) {
                continue;
            }

            // lock some
            $p->lockStock($pi['qty']);

           // $p->size = json_encode($data, JSON_UNESCAPED_UNICODE);
            $p->stock -= $pi['qty'];
            $p->stock = max($p->stock, 0);
            $p->recalcStock();
            $p->save();
        }

        // update order info
        $cart->rebuildData();
        if (!$cart->getProductCount()) {
            DB::query('delete from catalog_order_product where order_id = ?', [$order->order_id]);
            $order->delete();

            header('Location: /cart/');
            exit;
        }

        $order->sum_total = $cart->__sum_total;
        $order->sum_actual = $cart->__sum_actual;
        $order->qty = $cart->getTotalOrderCount();
        $order->save();
        $order->reload();

        // send the mail
        (new MailAssist($user->email, ($s ? 'Дозаказ' : 'Заказ') . ' №' . $order->order_id . ' создан'))
            ->alsoTo('form_email')
            ->tpl($s ? 'email_order_added.tpl' : 'email_order_new.tpl', ['order' => $order, 'user' => $user])
            ->send();

        $this->sendTelegram($order->last_name . ' ' . $order->name . ' ' . $order->patronym
            . ', ' . $order->phone . ', ' . $user->email, $order->order_id, $order->sum_actual, $s ? 'ДОЗАКАЗ' : 'НОВЫЙ ЗАКАЗ');

        // flush cart
        DB::query('delete from catalog_cart_product where cart_id = ?', [$cart->cart_id]);

        header('Location: /user/order/success/');
        exit;
    }

    protected function sendTelegramMessage(string $md)
    {
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, 'https://api.telegram.org/bot' . Core::siteParam('form_tg_token') . '/sendMessage');
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query([
            'chat_id' => Core::siteParam('form_tg_chat_id'),
            'parse_mode' => 'MarkdownV2',
            'text' => $md
        ]));

        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
    }


    protected function sendTelegram($user, $orderId, $sum, $text = 'НОВЫЙ ЗАКАЗ')
    {
        $patch = function ($i) {
            return str_replace(
                ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'],
                ['\\_', '\\*', '\\[', '\\]', '\\(', '\\)', '\\~', '\\`', '\\>', '\\#', '\\+', '\\-', '\\=', '\\|', '\\{', '\\}', '\\.', '\\!'],
                $i);
        };

        $md = <<<MD
**{$text} НА САЙТЕ НЕПОСЕДА**

**Клиент: ** {$patch($user)}
**ID заказа: ** {$patch($orderId)}
**Сумма: ** {$patch($sum)}

[Посмотреть заказ в админке]({$patch(url('/admin/shop/order/?action=form&order_id=' . $orderId))})
MD;

        $this->sendTelegramMessage($md);
        return true;
    }

    protected function sendTelegramOOS($productId, $productName, $size)
    {
        $patch = function ($i) {
            return str_replace(
                ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'],
                ['\\_', '\\*', '\\[', '\\]', '\\(', '\\)', '\\~', '\\`', '\\>', '\\#', '\\+', '\\-', '\\=', '\\|', '\\{', '\\}', '\\.', '\\!'],
                $i);
        };

        $md = <<<MD
**ТОВАР ЗАКОНЧИЛСЯ**

**Товар: ** {$patch($productName)}
**ID товара: ** {$patch($productId)}
**Размер: ** {$patch($size)}

[Посмотреть товар в админке]({$patch(url('/admin/shop/product/product/?action=form&product_id=' . $productId))})
MD;

        $this->sendTelegramMessage($md);
        return true;
    }
}