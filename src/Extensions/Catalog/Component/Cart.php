<?php
namespace App\Extensions\Catalog\Component;

use App\Extensions\Catalog\Model\Cart as CartModel;
use App\Extensions\Catalog\SessionAssist;
use Simflex\Core\Container;
use Simflex\Core\DB;
use Simflex\Extensions\Content\Content;

class Cart extends Content
{
    /** @var null|CartModel */
    public $cart = null;
    public $items = [];

    protected function content()
    {
        $this->cart = SessionAssist::$cart;
        if (!$this->cart) {
            $this->cart = SessionAssist::$cart = CartModel::getOrInsert(Container::getUser() ? Container::getUser()->user_id : 0);
        }

        if (($_REQUEST['action'] ?? '') && method_exists($this, $_REQUEST['action'])) {
            exit(json_encode($this->{$_REQUEST['action']}(), JSON_UNESCAPED_UNICODE));
        }

        $req = Container::getRequest();
        if (($uripart = $req->getUrlLastPart()) != 'cart' && method_exists($this, $uripart)) {
            $this->{$uripart}();
        }

        parent::content();
    }

    protected function updateCart()
    {
        return ['success' => $this->cart->addOrUpdateProduct($_REQUEST['product_id'], $_REQUEST['size'], $_REQUEST['qty'])];
    }

    protected function removeFromCart()
    {
        $this->cart->removeProduct($_REQUEST['product_id'], $_REQUEST['size']);
        return ['success' => true];
    }

    protected function resetCart()
    {
        DB::query('delete from catalog_cart_product where cart_id = ?', [$this->cart->cart_id]);
        $this->cart->rebuildData();
        header('Location: /cart/');
        exit;
    }

    protected function eraseUnavailable()
    {
        $prods = $this->cart->getProducts()['items'];
        foreach ($prods as $pi) {
            $p = $pi['product'];
            if (!$p->is_active || $p->stock <= 0) {
                $this->cart->removeProduct($p->product_id, $pi['size'], false);
            }

            foreach ($p->getSizesRaw() as $sz) {
                if ($sz['size'] == $pi['size'] && $sz['stock'] <= 0) {
                    $this->cart->removeProduct($p->product_id, $pi['size'], false);
                }
            }
        }

        $this->cart->rebuildData();

        header('Location: /cart/');
        exit;
    }

    protected function getCartInfo()
    {
        $items = [];
        foreach ($this->cart->getProducts()['items'] as $pi) {
            $p = $pi['product'];
            foreach ($p->getSizesRaw() as $sz) {
                if ($sz['size'] == $pi['size']) {
                    $p->stock = $sz['stock'];
                    break;
                }
            }

            $items[] = [
                'product_id' => $p->product_id,
                'img' => $p->getPreviewImage(),
                'price' => $p->price,
                'price_old' => $p->price_old,
                'path' => '/' . $p->path,
                'name' => $p->name,
                'size' => $pi['size'],
                'qty' => $pi['qty'],
                'sum' => $pi['sum_actual'],
                'stock' => $p->stock,
                'color' => $p->color,
                'is_sale' => $p->inSale(),
                'is_new' => $p->is_new,
                'is_popular' => $p->is_popular
            ];
        }

        return [
            'success' => true,
            'info' => [
                'sum' => $this->cart->sum_actual,
                'count' => $this->cart->getTotalOrderCount(),
            ],
            'items' => $items
        ];
    }
}