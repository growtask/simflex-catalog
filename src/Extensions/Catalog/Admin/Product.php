<?php

namespace App\Extensions\Catalog\Admin;

use App\Extensions\Catalog\CacheGen;
use App\Extensions\Catalog\CategoryAssist;
use App\Extensions\Catalog\Model\Category;
use App\Extensions\Catalog\Model\Product as ProductModel;
use App\Extensions\Catalog\Model\ProductParam;
use App\Extensions\Catalog\VkUpload;
use Simflex\Admin\Base;
use Simflex\Admin\Fields\FieldMultiKey;
use Simflex\Admin\Fields\FieldTags;
use Simflex\Admin\Plugins\Alert\Alert;
use Simflex\Core\DB;
use Simflex\Core\Helpers\Str;

class Product extends Base
{
    public function searchMultiKey()
    {
        $text = DB::escape($_REQUEST['text']);
        $name = str_replace(['filter[', ']'], '', $_REQUEST['name']);
        $field = $this->fields[$name];
        if (!($field instanceof FieldMultiKey)) {
            return [];
        }

        // WARNING
        // a temp hack is placed here to check additionally for a parent to include in the name

        $out = [];
        $q = DB::query("select `{$field->params['key']}` as id, `{$field->params['key_alias']}` as name, pid from 
                                      `{$field->params['table_values']}` where {$field->params['key_alias']} like '%$text%' limit 50");
        while ($r = DB::fetch($q)) {
            if ($r['pid']) {
                $par = DB::result('select name from catalog_category where category_id = ?', 0, [$r['pid']]);
                if ($par) {
                    $r['name'] .= ' (' . $par . ')';
                }
            }

            $out[] = $r;
        }

        exit(json_encode($out, JSON_UNESCAPED_UNICODE));
    }

    public function boolChange()
    {
        $old = $this->row['is_new'];

        $ret = parent::boolChange();
        if ($_REQUEST['field'] == 'is_active') {
            CategoryAssist::generateCountCache();
        }

        if ($_REQUEST['field'] == 'is_new' && $ret == 1 && !$old) {
            DB::query('update catalog_product set new_timeout = UNIX_TIMESTAMP() + 2630000 where product_id = ?', [
                $_REQUEST['pk']
            ]);
        }
    }

    public function save()
    {
        // recalc stock
        $_POST['stock'] = 0;
        foreach (json_decode($_POST['size'], true)['v'] ?? [] as $v) {
            $_POST['stock'] += $v['stock'];
        }

        $this->fields['stock']->readonly = false;

        $setNew = false;
        $isNew = !isset($_REQUEST['product_id']) || !$_REQUEST['product_id'];
        if ($isNew) {
            $setNew = true;
        } else {
            if (!DB::result('select is_new from catalog_product where product_id = ?', 0, [$_REQUEST['product_id']]) && $_POST['is_new'] == 'on') {
                $setNew = true;
            }
        }

        $ret = parent::save();
        if ($ret) {
            if ($_POST['is_new'] == 'on' && $setNew) {
                DB::query('update catalog_product set new_timeout = UNIX_TIMESTAMP() + 2630000 where product_id = ?', [$ret]);
            }

            $cats = explode(',', $_POST['p2c']);

            // refresh cache for their categories (might take a while to finish ...)
            $gen = new CacheGen();
            foreach ($cats as $c) {
                $gen->updateFor((int)$c);
            }

            CategoryAssist::generateCountCache();

            if ($isNew) {
                // update other npps
                DB::query('update catalog_product set npp = npp + 1 where product_id != ?', [$ret]);

                // force insert into categories
                foreach ($cats as $c) {
                   DB::query('insert into catalog_p2c (category_id, product_id) values (?, ?)', [$c, $ret]);
                }

                // update product-to-product
                $toAdd = explode(',', $_POST['p2p']);

                $toAddQ = [];
                foreach ($toAdd as $a) {
                    $toAddQ[] = "({$ret}, {$a})";
                }

                if (count($toAdd) > 1) {
                    for ($i = 0; $i < count($toAdd) - 1; ++$i) {
                        for ($k = $i + 1; $k < count($toAdd); ++$k) {
                            $toAddQ[] = "({$toAdd[$i]}, {$toAdd[$k]})";
                        }
                    }
                }

                $toAdd = implode(',', $toAddQ);
                DB::query(
                    "insert into catalog_p2p (left_id, right_id) values {$toAdd}"
                );
            }

            // update p2p cache for everything
            DB::query('delete from catalog_p2p_cache where source_id = ? or product_id = ?', [$ret, $ret]);

            $new = explode(',', $_POST['p2p']);
            foreach ($new as $n) {
                DB::query('delete from catalog_p2p_cache where source_id = ? or product_id = ?', [$n, $n]);
            }

            $this->updatePPCache($ret);
            foreach ($new as $n) {
                $this->updatePPCache($n);
            }
        }

        return $ret;
    }

    protected function updatePPCache($in)
    {
        $q2 = "SELECT
    CASE
        WHEN (p.left_id = t.product_id AND p.right_id IN ($in)) THEN p.right_id
        WHEN (p.right_id = t.product_id AND p.left_id IN ($in)) THEN p.left_id
        ELSE 0
        END AS source_id,
    t.product_id
FROM `catalog_product` t
         INNER JOIN catalog_p2p p ON
        (p.left_id = t.product_id AND p.right_id IN ($in))
        OR (p.right_id = t.product_id AND p.left_id IN ($in))
WHERE t.is_active = 1";

        $q2 = DB::query($q2);
        while ($r2 = DB::fetch($q2)) {
            DB::query('insert into catalog_p2p_cache (source_id, product_id) values (?, ?)', [
                $r2['source_id'], $r2['product_id']
            ]);
        }
    }

    public function deleteItem($id)
    {
        $cats = [];

        // save cats
        $q = DB::query('select category_id from catalog_p2c where product_id = ?', [$id]);
        while ($r = DB::fetch($q)) {
            $cats[] = $r['category_id'];
        }

        // detach from all cats
        DB::query('delete from catalog_p2c where product_id = ?', [$id]);

        // detach from other products
        DB::query('delete from catalog_p2p where left_id = ? or right_id = ?', [$id, $id]);

        // remove all custom parameters
        DB::query('delete from catalog_product_param_value where product_id = ?', [$id]);

        // update npps
        DB::query('update catalog_product set npp = npp - 1 where npp >= ?', [
            DB::result('select npp from catalog_product where product_id = ?', 0, [$id])
        ]);

        // delete elsewhere
        DB::query('delete from catalog_order_product where product_id = ?', [$id]);
        DB::query('delete from catalog_cart_product where product_id = ?', [$id]);
        DB::query('delete from catalog_fav_product where product_id = ?', [$id]);

        DB::query('delete from catalog_product_vk_queue where product_id = ?', [$id]);
        DB::query('delete from catalog_p2p_cache where source_id = ? or product_id = ?', [$id, $id]);

        parent::deleteItem($id);

        // update cats
        $gen = new CacheGen();
        foreach ($cats as $c) {
            $gen->updateFor((int)$c);
        }

        CategoryAssist::generateCountCache();
    }

    protected function showCell($field, $row)
    {
        if ($field->name == 'photo') {
            $tab = json_decode($row['photo'], true)['v'] ?? [];
            usort($tab, function ($a, $b) {
                return $a['npp'] > $b['npp'];
            });

            if (!$tab) {
                $img = asset('img/default-img.png');
            } else {
                $img = $tab[0]['img'];
            }

            echo '<div class="table__row-photo">
                            <img src="'.$img.'" alt="" />
                        </div>';
            return;
        }

        if ($field->name == 'price_old') {
            $tab = json_decode(DB::result('select size from catalog_product where product_id = ?', 0, [$row['product_id']]), true)['v'] ?? [];
            $val = implode(', ', array_map(function ($s) {
                return '<b>' . $s['size'] . '</b>: ' . $s['stock'];
            }, $tab));

            echo '<div class="table__row-text">'.$val.'</div>';
            return;
        }

        parent::showCell($field, $row); // TODO: Change the autogenerated stub
    }

    protected function showDetailExtra($row)
    {
        $tab = json_decode(DB::result('select size from catalog_product where product_id = ?', 0, [$row['product_id']]), true)['v'] ?? [];
        $val = implode(', ', array_map(function ($s) {
            return '<b>' . $s['size'] . '</b>: ' . $s['stock'];
        }, $tab));

        echo <<<HTML
    <tr class="modal-info__table-row">
        <th>Размеры</th>
        <td>$val</td>
    </tr>
HTML;

    }

    protected function showDetailPrepareValue($row, $key)
    {
        if ($key == 'photo') {
            $tab = json_decode($row['photo'], true)['v'] ?? [];
            usort($tab, function ($a, $b) {
                return $a['npp'] > $b['npp'];
            });

            if (!$tab) {
                $img = asset('img/default-img.png');
            } else {
                $img = $tab[0]['img'];
            }

            return '<img style="width: 3.75vw; height: 3.75vw; border-radius: 0.417vw; overflow: hidden" src="' . $img . '"/>';
        }

        return parent::showDetailPrepareValue($row, $key); // TODO: Change the autogenerated stub
    }

    protected function initTable()
    {
        parent::initTable();

        // load params
        if (!isset($_REQUEST[$this->pk->name])) {
            return;
        }

        $prod = ProductModel::findOne(['product_id' => $_REQUEST[$this->pk->name]]);
        if (!$prod) {
            return;
        }

        $params = ProductParam::findAdv()->where('is_active = 1')->orderBy('npp')->all();
        $cats = $prod->getAllCategories();

        $toAdd = [];
        /** @var ProductParam $p */
        foreach ($params as $p) {
            if (!$p->hasAnyCategory()) {
                $toAdd[] = $p;
                continue;
            }

            foreach ($cats as $c) {
                if ($p->inCategory($c->category_id)) {
                    $toAdd[] = $p;
                    continue;
                }

                /** @var \App\Extensions\Catalog\Model\Category $cc */
                $cc = $c;
                while ($cc = $cc->getParent()) {
                    if ($p->inCategory($cc->category_id)) {
                        $toAdd[] = $p;
                        continue 2;
                    }
                }
            }
        }

        /** @var ProductParam $i */
        foreach ($toAdd as $i) {
            $f = new FieldTags([
                'name' => $i->key,
                'label' => $i->name,
                'table' => $this->table
            ]);

            $f->isVirtual = true;
            $f->params = [
                'pos' => 'right',
                'pos_group' => 'Параметры товара',
                '__param' => $i->toArray(),
            ];

            $f->value = DB::result(
                'select value from catalog_product_param_value where product_id = ? and param_id = ?',
                'value',
                [
                    $_GET[$this->pk->name],
                    $i->param_id,
                ]
            );

            $f->values = $i->getValues();
            $this->fields[$i->key] = $f;
        }
    }

    protected function getQueryInsert()
    {
        if ($r = $this->updatePath()) {
            return [$r, []];
        }

        $this->updateParameters();

        // force update path
        $this->fields['path']->readonly = false;
        return parent::getQueryInsert();
    }

    protected function getQueryUpdate()
    {
        // erase everything for this product
        DB::query('DELETE FROM catalog_product_param_value WHERE product_id = ?', [$_REQUEST[$this->pk->name]]);
        if ($r = $this->updatePath()) {
            return [$r, []];
        }

        $this->updateParameters();

        // force update path
        $this->fields['path']->readonly = false;
        return parent::getQueryUpdate();
    }

    protected function updateParameters()
    {
        /**
         * @var string $k
         * @var \Simflex\Admin\Fields\Field $f
         */
        foreach ($this->fields as $k=>$f) {
            if (!$f->isVirtual || !isset($f->params['__param']) || !($f instanceof FieldTags)) {
                continue;
            }

            $val = $f->getPOST();
            DB::query('INSERT INTO catalog_product_param_value (product_id, param_id, value) VALUES (?, ?, ?)', [
                $_REQUEST[$this->pk->name], $f->params['__param']['param_id'], $val
            ]);

            // refresh value
            $f->value = $val;
            foreach (explode(',', $val) as $v) {
                $f->values[] = $v;
            }

            $f->values = array_unique($f->values);
        }
    }

    protected function updatePath()
    {
        $cats = explode(',', $_POST['p2c']);
        if (!$cats) {
            Alert::error('Не указана ни одна категория');
            return 'ERROR';
        }

        // compose path
        $cat = null;
        foreach ($cats as $c) {
            if (DB::result('select is_sale from catalog_category where category_id = ?', 0, [$c])) {
                continue;
            }

            $cat = Category::findOne(['category_id' => $c]);
            break;
        }

        if (!$cat) {
            $cat = Category::findOne(['category_id' => $cats[0]]);
        }

        if (!$cat) {
            Alert::error('Не удалось найти категорию');
            return 'ERROR';
        }

        if (!$_POST['alias']) {
            $_POST['alias'] = Str::translite($_POST['name']);
        }

        $_POST['path'] = $cat->path . '/' . $_POST['alias'];
        return '';
    }

    public function showActions()
    {
        echo <<<HTML
    <a href="?action=vkUpload" class="BtnPrimarySm BtnIconLeft">
        Выгрузить в VK
    </a>
HTML;

        parent::showActions();
    }

    protected function vkUpload()
    {
        $u = new VkUpload();
        $u->authorize();
    }

    protected function vkUploadAuth()
    {
        $u = new VkUpload();
        if (!$u->authorizeEnd()) {
            $this->show();
            exit;
        }

        $u->makeQueue();
        header('Location: ./?action=vkUploadProcess');
        exit;
    }

    public function show()
    {
        $this->fields['price_old']->label = 'Размеры';
        parent::show();
    }

    protected function vkUploadProcess()
    {
        $u = new VkUpload();
        $ret = $u->runStep();
        if ($ret == VkUpload::STATUS_DONE) {
            header('Location: ./?action=show');
            exit;
        }

        $next = $u->getNextStep();

        $tot = DB::result('select count(*) c from catalog_product_vk_queue', 0);
        $fin = DB::result('select count(*) c from catalog_product_vk_queue where is_uploaded = 1', 0);

        include 'tpl/vku.tpl';
    }
}