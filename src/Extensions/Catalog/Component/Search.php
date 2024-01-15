<?php
namespace App\Extensions\Catalog\Component;

use App\Extensions\Catalog\Model\Product;
use App\Extensions\Catalog\SessionAssist;
use Simflex\Core\ComponentBase;
use Simflex\Core\DB;
use Simflex\Core\DB\AQ;

class Search extends ComponentBase
{
    protected function content()
    {
        if (($_REQUEST['a'] ?? '') == 'city') {
            $this->searchCity();
        }

        if (($_REQUEST['a'] ?? '') == 'stats') {
            $this->getStats();
        }

        $inp = DB::escape($_REQUEST['q']);

        $out = ['items' => [], 'tot' => Product::findAdv()->select('count(*)')
                ->where("(name like '%$inp%' or sku like '%$inp%')")
                ->andWhere('is_active = 1')
                ->fetchScalar() ?? 0];

        $prods = Product::findAdv()
            ->where("(name like '%$inp%' or sku like '%$inp%')")
            ->andWhere('is_active = 1')
            ->limit('10')->all();
        foreach ($prods as $p) {
            $out['items'][] = [
                'product_id' =>  $p->product_id,
                'img' => $p->getPreviewImage(),
                'name' =>  $p->name,
                'price' =>  $p->price,
                'price_old' =>  $p->price_old,
                'sizes' =>  $p->getSizesStr(),
                'stock' =>  $p->stock,
                'path' => $p->path
            ];
        }

        exit(json_encode($out, JSON_UNESCAPED_UNICODE));
    }

    protected function getStats()
    {
        $out = [];

        $prods = Product::findAdv()->where('product_id in (' . DB::escape($_REQUEST['ids'] . ')'))->all();

        /** @var Product $p */
        foreach ($prods as $p) {
            $out[] = [
                'id' => $p->product_id,
                'in_cart' => SessionAssist::$cart->inCart($p->product_id),
                'in_fav' => $p->inFav(),
                'pp' => $p->getPPReadyInfo2()
            ];
        }

        exit(json_encode(['items' => $out], JSON_UNESCAPED_UNICODE));
    }

    protected function searchCity()
    {
        $inp = DB::escape($_REQUEST['q']);

        $out = ['items' => []];
        $q = DB::query('select name from geo_city c where (select country_id from geo_region r where r.id = c.region_id) = 14 and name like \'%'.$inp.'%\' limit 50');
        while ($r = DB::fetch($q)) {
            $out['items'][] = $r['name'];
        }

        exit(json_encode($out, JSON_UNESCAPED_UNICODE));
    }
}