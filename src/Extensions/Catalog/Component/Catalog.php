<?php
namespace App\Extensions\Catalog\Component;

use App\Extensions\Catalog\Model\Category;
use App\Extensions\Catalog\Model\FakeCategory;
use App\Extensions\Catalog\Model\Product;
use App\Extensions\Catalog\SessionAssist;
use App\Plugins\GeoIp\GeoIp;
use MongoDB\Driver\Session;
use Simflex\Core\Container;
use Simflex\Core\DB;
use Simflex\Core\Page;
use Simflex\Core\Profiler;
use Simflex\Extensions\Breadcrumbs\Breadcrumbs;
use Simflex\Extensions\Content\Model\ModelContent;

class Catalog extends \Simflex\Extensions\Content\Content
{
    protected $cat;
    protected $prod;

    protected $path = '/catalog/';

    protected $pars = [];
    protected $filters = [];

    protected function dropNulls()
    {
        if (!isset($_REQUEST['filter'])) {
            return;
        }

        $shouldExit = false;

        $q = ['price' => $_REQUEST['price']];
        foreach ($_REQUEST['filter'] as $k=>$v) {
            if ($v) {
                $q['filter'][$k] = $v;
            } else {
                $shouldExit = true;
            }
        }

        if (isset($_REQUEST['search'])) {
            $q['search'] = $_REQUEST['search'];
        }

        if ($shouldExit) {
            header('Location: ./?' . http_build_query($q));
            exit;
        }
    }

    public function get(): ?ModelContent
    {
        // drop off unused filters
        //$this->dropNulls();

        Profiler::traceStart(__CLASS__, __FUNCTION__);
        if ($content = ModelContent::findOne(['path' => $this->path, 'active' => 1])) {
            $content['params'] = unserialize($content['params']);
        }

        if ($_REQUEST['q'] ?? '') {
            $content['title'] = 'Поиск';
        } elseif ($_REQUEST['wl'] ?? 0) {
            $content['title'] = 'Избранное';
        } else {
            Page::$override['description'] = $this->cat->meta_desc;
            Page::$override['keywords'] = $this->cat->meta_keywords;

            if ($this->prod) {
                $content['title'] = $this->prod->name;
                Page::$override['title'] = $this->prod->meta_title ?: (
                    'Купить оптом ' . $this->prod->name . ' недорого в городе ' . GeoIp::getCurrentCity() . ' ' . $this->prod->sku
                );

                if ($this->prod->meta_title) {
                    Page::$override['uses_meta'] = true;
                }

                Page::$override['description'] = $this->prod->meta_desc ?: (
                    'Купить оптом '. $this->prod->name . ' ' . $this->prod->getSizesStr() . ' размера в городе ' .
                    GeoIp::getCurrentCity() . ' по низкой цене. Минимальный заказ всего от 3000 рублей, быстрая сборка заказов, отгрузка сразу после оплаты.'
                );

                Page::$override['keywords'] = $this->prod->meta_keywords;
            } else {
                $content['title'] = $this->cat->name;
                Page::$override['title'] = $this->cat->meta_title ?: (
                    'Купить оптом ' . $this->cat->name . ' недорого в городе ' . GeoIp::getCurrentCity()
                );

                if ($this->cat->meta_title) {
                    Page::$override['uses_meta'] = true;
                }

                Page::$override['description'] = $this->cat->meta_desc ?: (
                    'Купить оптом '. $this->cat->name . ' в городе ' .
                    GeoIp::getCurrentCity() . ' по низкой цене. Минимальный заказ всего от 3000 рублей, быстрая сборка заказов, отгрузка сразу после оплаты.'
                );
            }
        }

        Profiler::traceEnd();
        return $content;
    }

    protected function content()
    {
        Profiler::traceStart(__CLASS__, __FUNCTION__);
        $this->cat = $this->resolveCategory();
        Breadcrumbs::remove('/catalog/product/');

        // load filters
        $this->filters = $this->getFilters();

        if ($this->prod) {
            $this->path = '/catalog/product/';
            Breadcrumbs::add($this->prod->name, '/' . $this->prod->path);
        }

        if ($this->cat->category_id) {
            Breadcrumbs::add($this->cat->name, '/' . $this->cat->path);

            if ($this->cat->pid) {
                $pars = [];

                $cat = $this->cat;
                while ($cat = $cat->getParent()) {
                    if (!$cat->is_active) {
                        continue;
                    }

                    $pars[] = [$cat->name, '/' . $cat->path];
                }

                foreach ($pars as $p) {
                    Breadcrumbs::add($p[0], $p[1]);
                }
            }
        }

        if ($_REQUEST['q'] ?? '') {
            Breadcrumbs::add('Поиск', '/search/?q=' . $_REQUEST['q']);
        } elseif ($_REQUEST['wl'] ?? 0) {
            Breadcrumbs::add('Избранное', '/favorite/');
        } else {
            Breadcrumbs::add('Каталог', '/catalog/');
        }

        $r = Container::getRequest();
        if ($r->isAjax()) {
            Profiler::traceEnd();
            exit($this->ajaxContent());
        }

        $sort = 'npp ASC';
        if ($r->request('sort')) {
            switch ($r->request('sort')) {
                case 'order_desc':
                    $sort = 'price DESC';
                    break;
                case 'order_asc':
                    $sort = 'price ASC';
                    break;
                case 'name_desc':
                    $sort = 'name DESC';
                    break;
                case 'name_asc':
                    $sort = 'name ASC';
                    break;
            }
        }

        $selected = $this->makeSelected();

        $this->pars['sort'] = $sort;
        $this->pars['selected'] = $selected;

        // min/max price
        $this->pars['pmin'] = DB::result('SELECT price FROM catalog_product ORDER BY price ASC LIMIT 1', 'price');
        $this->pars['pmax'] = DB::result('SELECT price FROM catalog_product ORDER BY price DESC LIMIT 1', 'price');

        Profiler::traceEnd();
        parent::content();
    }

    protected function getFilters()
    {
        Profiler::traceStart(__CLASS__, __FUNCTION__);
        if (!($_REQUEST['q'] ?? '') && !($_REQUEST['wl'] ?? '')) {
            return $this->cat->getFilters();
        }

        $t = $this->cat->getFilters();
        $f = [];

        // this is ugly.
        // would be nice to find a way to optimize this crap
        foreach ($t['filters'] as $ff) {
            if ($this->cat->getProductCount($this->makeFilter(['filter' => [$ff => 1]]))) {
                $f['filters'][] = $ff;
            }
        }

        foreach ($t['sizes'] as $ff) {
            if ($this->cat->getProductCount($this->makeFilter(['filter' => ['size' => [$ff]]]))) {
                $f['sizes'][] = $ff;
            }
        }

        foreach ($t['param_values'] as $pp=>$ff) {
            $p = null;
            foreach ($t['params'] as $par) {
                if ($par->param_id == $pp) {
                    $p = $par;
                    break;
                }
            }

            // this shouldn't really happen but at this point nobody can be trusted
            if (!$p) {
                continue;
            }

            $any = false;
            foreach ($ff as $fff) {
                if ($this->cat->getProductCount($this->makeFilter(['search' => [$p->key => [$fff]]]))) {
                    $f['param_values'][$pp][] = $fff;
                    $any = true;
                }
            }

            if ($any) {
                $f['params'][] = $p;
            }
        }

        Profiler::traceEnd();
        return $f;
    }

    protected function breadcrumbs($content)
    {
        // stub this
    }

    protected function makeSelected()
    {
        $out = [];

        $r = Container::getRequest();
        if ($p = $r->request('filter')) {
             if ($p['is_new'] ?? false) {
                 $out['filter[is_new]'] = 'Новинки';
             }

            if ($p['stock'] ?? false) {
                $out['filter[stock]'] = 'В наличии';
            }

            if ($p['is_popular'] ?? false) {
                $out['filter[is_popular]'] = 'Популярное';
            }

            if ($p['is_sale'] ?? false) {
                $out['filter[is_sale]'] = 'Скидки';
            }

            if ($p['size'] ?? false) {
                $s = [];
                foreach ($p['size'] as $m) {
                    $s[] = $m;
                }

                $out['filter[size][]'] = ['Размер', $s];
            }
        }

        if ($p = $r->request('search')) {
            foreach ($this->filters['params'] as $f) {
                if ($p[$f->key] ?? false) {
                    $s = [];
                    foreach ($p[$f->key] as $m) {
                        $s[] = $m;
                    }

                    $out['search['.$f->key.'][]'] = [$f->name, $s];
                }
            }
        }

        return $out;
    }

    protected function makeSearchFilter(string $param, string $value) {
        $param = DB::wrapName($param);
        $value = DB::escape($value);
        return "($param = '$value' or $param like '$value,%' or $param like '%,$value' or $param like '%,$value,%')";
    }

    protected function makeJsonSearchFilter(string $param, string $inp, string $value) {
        $param = DB::wrapName($param);
        $value = DB::escape($value);
        $inp = DB::escape($inp);
        return "($param like '%{\"$inp\":\"$value\"%' or $param like '%,\"$inp\":\"$value\"}%')";
    }

    protected function makeQFilter()
    {
        $filter = '';

        if ($q = ($_REQUEST['q'] ?? '')) {
            $filter .= '(name like \'%' . DB::escape($q) . '%\' or sku like \'%' . DB::escape($q) . '%\') ';
        }

        return $filter;
    }

    protected function makeWFilter()
    {
        $filter = '';

        if ($_REQUEST['wl'] ?? 0) {
            $fav = SessionAssist::$fav;
            $filter .= '(t.product_id in (select wl.product_id from catalog_fav_product wl where fav_id = '.$fav->fav_id.')) ';
        }

        return $filter;
    }

    protected function makeFilter($override = [])
    {
        $filter = $this->makeQFilter();
        if ($f2 = $this->makeWFilter()) {
            if ($filter) {
                $filter .= ' AND ';
            }

            $filter .= $f2;
        }

        // this will probably introduce a bug
        // but i really don't care anymore
        $r = Container::getRequest();
        $rr = [
            'price' => $override['price'] ?? $r->request('price'),
            'filter' => $override['filter'] ?? $r->request('filter'),
            'search' => $override['search'] ?? $r->request('search'),
        ];

        if ($price = $rr['price']) {
            if ($filter) {
                $filter .= ' AND ';
            }

            $filter .= 'price >= ' . DB::escape($price['min']) . ' AND ';
            $filter .= 'price <= ' . DB::escape($price['max']) . ' ';
        }

        if ($p = $rr['filter']) {
            if ($p['is_new'] ?? false) {
                if ($filter) {
                    $filter .= ' AND ';
                }

                $filter .= 'is_new = 1';
            }

            if ($p['stock'] ?? false) {
                if ($filter) {
                    $filter .= ' AND ';
                }

                $filter .= 'stock > 0 AND stock is not NULL';
            }

            if ($p['is_popular'] ?? false) {
                if ($filter) {
                    $filter .= ' AND ';
                }

                $filter .= 'is_popular = 1';
            }

            if ($p['is_sale'] ?? false) {
                if ($filter) {
                    $filter .= ' AND ';
                }

                $filter .= 'price_old > 0 and price_old > price';
            }

            if ($p['size'] ?? false) {
                if ($filter) {
                    $filter .= ' AND ';
                }

                $filter .= '(';

                $first = true;
                foreach ($p['size'] as $s) {
                    if (!$first) {
                        $filter .= ' or ';
                    }

                    $filter .= $this->makeJsonSearchFilter('size', 'size', $s);
                    $first = false;
                }

                $filter .= ')';
            }
        }

        if ($p = $rr['search']) {
            if ($filter) {
                $filter .= ' AND ';
            }

            $filter .= '(';

            $first = true;
            foreach ($p as $k=>$a) {
                if (!$first) {
                    $filter .= ' and ';
                }

                $filter .= '(select count(*) from catalog_product_param_value vv where t.product_id = vv.product_id and (';

                $first2 = true;
                foreach ($a as $v) {
                    if (!$first2) {
                        $filter .= ' or ';
                    }

                    $filter .= $this->makeSearchFilter('value', $v);
                    $first2 = false;
                }

                $filter .= ')) > 0 ';

                $first = false;
            }

            $filter .= ') ';
        }

        return $filter;
    }

    protected function ajaxCount()
    {
        return $this->cat->getProductCount($this->makeFilter());
    }

    protected function ajaxContent()
    {
        $r = Container::getRequest();

        if ($act = $r->request('action')) {
            switch ($act) {
                case 'count':
                    return $this->ajaxCount();
            }
        }

        return '';
    }

    private function resolveCategory()
    {
        Profiler::traceStart(__CLASS__, __FUNCTION__);
        $r = Container::getRequest();
        $path = $r->getPath();

        if (str_contains($path, 'favorite/')) {
            $_REQUEST['wl'] = 1;
        }

        if (str_contains($path, 'search/') && !($_REQUEST['q'] ?? '')) {
            header('Location: /catalog/');
            exit;
        }

        $path = str_replace(['search/', 'favorite/'], 'catalog/', $path);

        if ($path == '/catalog/') {
            Profiler::traceEnd();
            return new FakeCategory();
        }

        // locate category
        $cat = Category::findAdv()->where(['path' => trim($path, '/')])->fetchOne();
        if ($cat) {
            Profiler::traceEnd();
            return $cat;
        }

        // attempt loading product
        $prod = Product::findAdv()->where(['path' => trim($path, '/'), 'is_active' => 1])->fetchOne();
        if ($prod) {
            $this->prod = $prod;

            // roll back cat path
            $path = explode('/', trim($path, '/'));
            unset($path[count($path) - 1]);

            while (!($cat = Category::findAdv()->where(['path' => implode('/', $path)])->fetchOne())) {
                unset($path[count($path) - 1]);
            }
        }

        Profiler::traceEnd();

        // send to 404
        if (!$cat && !$prod) {
            header('Location: /404');
            exit;
        }

        // check cat still, should be there
        $cat ??= new FakeCategory();
        return $cat;
    }
}