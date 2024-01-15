<?php
namespace App\Extensions\Catalog\Admin;

use App\Extensions\Catalog\MailAssist;
use Dompdf\Dompdf;
use PhpOffice\PhpWord\TemplateProcessor;
use Simflex\Admin\Base;
use Simflex\Admin\Plugins\Alert\Alert;
use Simflex\Core\Container;
use Simflex\Core\Core;
use Simflex\Core\DB;
use Simflex\Core\Helpers\Str;
use Simflex\Core\Models\User;
use Simflex\Core\Time;

class Order extends Base
{
    protected $statusToNumMap = [
        'new' => 0,
        'validating' => 1,
        'accepted' => 2,
        'ready' => 3,
        'pay' => 4,
        'added' => 5,
        'sent' => 6,
        'delivered' => 7,
        'finished' => 8,
        'canceled' => 9,
    ];

    protected $statusToTextMap = [
        'new' => 'Новый',
        'accepted' => 'Принят',
        'ready' => 'Собран',
        'sent' => 'Отправлен',
        'delivered' => 'Доставлен',
        'finished' => 'Завершен',
        'canceled' => 'Отменен',
        'added' => 'Дозаказ',
        'validating' => 'На уточнении',
        'pay' => 'Ожидает оплаты',
    ];

    protected function prepareWhere()
    {
        parent::prepareWhere();

        foreach ($this->where as &$q) {
            if (str_starts_with($q, 'catalog_order.last_name')) {
                $vs = strpos($q, '\'%') + 2;
                $ve = strpos($q, '%\'');
                $val = explode(' ', substr($q, $vs, $ve - $vs));
                if (!$val) {
                    continue;
                }

                $add = [];
                $q = '(COALESCE(catalog_order.user_last_name, useruser_id.last_name) like \'%' . $val[0] . '%\' OR ';
                $add[] = 'COALESCE(catalog_order.user_name, useruser_id.name) like \'%' . $val[0] . '%\'';
                $add[] = 'COALESCE(catalog_order.user_patronym, useruser_id.patronym) like \'%' . $val[0] . '%\'';

                for ($i = 1; $i < count($val); ++$i) {
                    $add[] = 'COALESCE(catalog_order.user_last_name, useruser_id.last_name) like \'%' . $val[$i] . '%\'';
                    $add[] = 'COALESCE(catalog_order.user_name, useruser_id.name) like \'%' . $val[$i] . '%\'';
                    $add[] = 'COALESCE(catalog_order.user_patronym, useruser_id.patronym) like \'%' . $val[$i] . '%\'';
                }

                $q .= implode(' OR ', $add) . ')';
            }
        }
    }

    public function show()
    {
        $this->fields['last_name']->label = 'ФИО, ТК, адрес';
        $this->fields['comment']->label = 'Дата изменения статуса';
        $this->selectAdditional = ['catalog_order.name', 'catalog_order.patronym', 'catalog_order.transcomp', 'catalog_order.city', 'catalog_order.address'];

        parent::show();
    }

    public function rowActions($row, $i)
    {
        include 'tpl/order_row.tpl';
    }

    protected function showDetailPrepareValue($row, $key)
    {
        $val = parent::showDetailPrepareValue($row, $key);
        if ($key == 'last_name') {
            $q = DB::query('select name, patronym from catalog_order where order_id = ?', [$row['order_id']]);
            $r = DB::fetch($q);

            $val .= ' ' . $r['name'] . ' ' . $r['patronym'];
            $this->fields[$key]->label = 'Получатель';
        }

        if ($key == 'phone' && !$val) {
            $q = DB::query('select phone from user where user_id = ?', [$row['user_id']]);
            $r = DB::fetch($q);

            $val = $r['phone'];
        }

        return $val;
    }

    protected function showDetailExtra($row)
    {
        $owner = (new \App\Extensions\Catalog\Model\Order($row['order_id']))->user;

        return <<<HTML
    <tr class="modal-info__table-row">
        <th>Покупатель</th>
        <td>{$owner->last_name} {$owner->name} {$owner->patronym}</td>
    </tr>
HTML;

    }

    protected function showCell($field, $row)
    {
        if ($field->name == 'last_name') {
            $u = new User($row['user_id']);
            $row['last_name'] = '<strong>' . ($row['user_last_name'] ?: $u->last_name) . ' ' .
                ($row['user_name'] ?: $u->name) . ' ' . ($row['user_patronym'] ?: $u->patronym) . '</strong>';
            if ($u->transcomp) {
                $row['last_name'] .= '<br/><em>' . $u->transcomp . '</em>';
            }
            $row['last_name'] .= '<br/> г. ' . $u->city . ', ' . $u->address;
        }

        if ($field->name == 'phone' && !$row['phone']) {
            $u = new User($row['user_id']);
            $row['phone'] = ($row['user_phone'] ?? $u->phone);
        }

        if ($field->name == 'comment') {
            $editedOn = DB::result('select edited_on from catalog_order where order_id = ?', 0, [$row['order_id']]);
            $row['comment'] = 'Изменено на <b>' . $this->statusToTextMap[$row['status']] . '</b><br/>' . Time::normal($editedOn ?: $row['date']);
        }

        parent::showCell($field, $row);
    }

    public function vtAdd()
    {
        $pk = $_REQUEST[$this->pk->name];
        $product = $_REQUEST['product'];
        $size = $_REQUEST['size'];
        $qty = $_REQUEST['qty'];

        $ord = \App\Extensions\Catalog\Model\Order::findOne(['order_id' => $pk]);
        $prod = \App\Extensions\Catalog\Model\Product::findOne(['product_id' => $product]);

        if (!$prod || !$ord) {
            exit(json_encode(['success' => false]));
        }

        $oldSzStock = 0;

        $data = json_decode($prod->size, true) ?? [];
        if ($data) {
            foreach ($data['v'] as &$sz) {
                if ($sz['size'] == $size && $sz['stock'] > 0) {
                    $qty = max(min($qty, $sz['stock']), 0);
                    $oldSzStock = $sz['stock'];
                    $sz['stock'] -= $qty;
                }
            }
        }

        if ($oldSzStock) {
            $prod->size = json_encode($data, JSON_UNESCAPED_UNICODE);
            $prod->stock -= $qty;
            $prod->stock = max($prod->stock, 0);
            $prod->recalcStock();
            $prod->save();

            $price = ($_REQUEST['price'] ?? 0) ?: $prod->__price;
            $priceOld = ($_REQUEST['price_old'] ?? 0) ?: $prod->__price_old;

            DB::query(
                'insert into catalog_order_product (order_id, product_id, qty, sum, size, is_deleted, is_changed, is_added, stock, price, price_old) values (?, ?, ?, ?, ?, 0, 0, 1, ?, ?, ?)',
                [
                    $pk,
                    $product,
                    $qty,
                    $price * $qty,
                    $size,
                    $oldSzStock,
                    $price,
                    $priceOld,
                ]
            );

            $ord->rebuildData();
            exit(json_encode(['success' => true]));
        }
    }

    public function vtEdit()
    {
        $pk = $_REQUEST[$this->pk->name];
        $id = $_REQUEST['id'];
        $product = $_REQUEST['product'];
        $size = $_REQUEST['size'];
        $qty = $_REQUEST['qty'];
        $price = $_REQUEST['price'];
        $priceOld = $_REQUEST['price_old'];

        $ord = \App\Extensions\Catalog\Model\Order::findOne(['order_id' => $pk]);
        $prod = \App\Extensions\Catalog\Model\Product::findOne(['product_id' => $product]);

        $data2 = DB::query('select qty, size, price from catalog_order_product where order_product_id = ?', [$id]);
        $data2 = DB::fetch($data2);

        $data = json_decode($prod->size, true) ?? [];
        if ($data) {
            foreach ($data['v'] as &$sz) {
                if ($sz['size'] == $size) {
                    $sz['stock'] += $data2['qty'] - $qty;
                    $sz['stock'] = max($sz['stock'], 0);
                }
            }
        }

        $prod->size = json_encode($data, JSON_UNESCAPED_UNICODE);
        $prod->stock += $data2['qty'] - $qty;
        $prod->stock = max($prod->stock, 0);
        $prod->recalcStock();
        $prod->save();

        DB::query('update catalog_order_product set qty = ?, size = ?, sum = ?, price = ?, price_old = ?, is_added = 0, is_deleted = 0, is_changed = 1 where order_product_id = ?', [
            $qty, $size, $price * $qty, $price, $priceOld, $id
        ]);

        $ord->rebuildData();
        exit(json_encode(['success' => true]));
    }

    public function getSizes()
    {
        $id = (int)$_REQUEST['id'];
        $q = DB::query('select size from catalog_product where product_id = ?', [$id]);
        $r = DB::fetch($q);

        $ret = '';
        foreach (json_decode($r['size'], true)['v'] ?? [] as $s) {
            $ret .= '<div data-value="'.$s['size'].'" class="form-control__dropdown-item">'.$s['size'].'</div>';
        }

        exit($ret);
    }

    public function vtDelete()
    {
        $id = (int)$_REQUEST['id'];
        $data = DB::query('select product_id, qty, size from catalog_order_product where order_product_id = ?', [$id]);
        $data = DB::fetch($data);

        $prod = \App\Extensions\Catalog\Model\Product::findOne(['product_id' => $data['product_id']]);
        $prod->stock += $data['qty'];

        $data2 = json_decode($prod->size, true) ?? [];
        if ($data2) {
            foreach ($data2['v'] as &$sz) {
                if ($sz['size'] == $data['size']) {
                    $sz['stock'] += $data['qty'];
                }
            }
        }

        $prod->size = json_encode($data2, JSON_UNESCAPED_UNICODE);
        $prod->recalcStock();
        $prod->save();

        DB::query('update catalog_order_product set is_deleted = 1, is_added = 0, is_changed = 0 where order_product_id = ?', [$id]);

        $ord = new \App\Extensions\Catalog\Model\Order(DB::result('select order_id from catalog_order_product where order_product_id = ?', 0, [$id]));
        $ord->rebuildData();

        exit(json_encode(['success' => true]));
    }

    public function vtGet()
    {
        exit(json_encode([
            'total' => $this->fields['products']->getCount(),
            'v' => $this->fields['products']->getValue()
        ], JSON_UNESCAPED_UNICODE));
    }

    public function searchProducts()
    {
        $out = [];
        $q = DB::query('select product_id as id, name from catalog_product where name like \'%'.DB::escape($_REQUEST['text']).'%\' limit 50');
        while ($r = DB::fetch($q)) {
            $out[] = $r;
        }

        exit(json_encode($out));
    }

    public function searchString()
    {
        exit(json_encode(DB::assoc('select name as id, name from catalog_transcomp where name like \'%'.DB::escape($_REQUEST['text']).'%\' limit 50')));
    }

    public function showActions()
    {
        $icons = asset('img/icons/svg-defs.svg');
        echo <<<HTML
    <script src="/Extensions/Catalog/Admin/js/order.js"></script>
    <button onclick="exportOrder('schet')" class="BtnSecondaryMonoSm BtnIconLeft action-with-select" disabled>
        <svg class="notification__close-icon" fill="transparent" stroke="#ffffff" viewBox="0 0 24 24">
            <use xlink:href="$icons#download"></use>
        </svg>
        Счет
    </button>
    <button onclick="exportOrder('factura')" class="BtnSecondaryMonoSm BtnIconLeft action-with-select" disabled>
        <svg class="notification__close-icon" fill="transparent" stroke="#ffffff" viewBox="0 0 24 24">
            <use xlink:href="$icons#download"></use>
        </svg>
        Счет-Фактура
    </button>
    <button onclick="exportOrder('ttn')" class="BtnSecondaryMonoSm BtnIconLeft action-with-select" disabled>
        <svg class="notification__close-icon" fill="transparent" stroke="#ffffff" viewBox="0 0 24 24">
            <use xlink:href="$icons#download"></use>
        </svg>
        ТТН
    </button>
    <button onclick="mergeOrder()" class="BtnPrimarySm BtnIconLeft action-with-select" disabled>
        <svg class="notification__close-icon" fill="transparent" stroke="#ffffff" viewBox="0 0 24 24">
            <use xlink:href="$icons#copy"></use>
        </svg>
        Объединить
    </button>
HTML;

        parent::showActions();
    }

    public function merge()
    {
        $ids = explode(',', $_REQUEST['ids']);
        $ids = array_filter($ids, function ($a) {
            return !!(int)$a;
        });

        if (count($ids) < 2) {
            Alert::error('Необходимо выбрать как минимум 2 заказа');
            header('Location: ./?action=show');
            exit;
        }

        // sort in asc
        sort($ids);

        $list = [];
        $into = new \App\Extensions\Catalog\Model\Order($ids[0]);
        if ($into->status == 'canceled') {
            Alert::error('Один или несколько заказов находятся в статусе Отменен');
            header('Location: ./?action=show');
            exit;
        }

        // check users first
        for ($i = 1; $i < count($ids); ++$i) {
            $ord = new \App\Extensions\Catalog\Model\Order($ids[$i]);
            if ($ord->user_id != $into->user_id) {
                Alert::error('Все заказы должны быть от одного и того же пользователя');
                header('Location: ./?action=show');
                exit;
            }

            if ($ord->status == 'canceled') {
                Alert::error('Один или несколько заказов находятся в статусе Отменен');
                header('Location: ./?action=show');
                exit;
            }

            $list[] = $ord;
        }

        // merge now
        /** @var \App\Extensions\Catalog\Model\Order $o */
        foreach ($list as $o) {
            foreach ($o->getProducts('', '', 'COALESCE(is_deleted, 0) = 0') as $p) {
                if (DB::result('select count(*) from catalog_order_product where product_id = ? and size = ? and price = ? and price_old = ? and order_id = ?', 0, [
                    $p['product']->product_id,
                    $p['size'],
                    $p['price'],
                    $p['price_old'],
                    $into->order_id
                ]) > 0) {
                    DB::query('update catalog_order_product set qty = qty + ? where product_id = ? and size = ? and price = ? and price_old = ? and order_id = ?', [
                        $p['qty'],
                        $p['product']->product_id,
                        $p['size'],
                        $p['price'],
                        $p['price_old'],
                        $into->order_id
                    ]);
                } else {
                    DB::query('update catalog_order_product set order_id = ? where product_id = ? and size = ? and price = ? and price_old = ? and order_id = ?', [
                        $into->order_id,
                        $p['product']->product_id,
                        $p['size'],
                        $p['price'],
                        $p['price_old'],
                        $o->order_id,
                    ]);
                }

                if (DB::error()) {
                    Alert::error(DB::error());
                    header('Location: ./?action=show');
                    exit;
                }
            }

            DB::query('delete from catalog_order_product where order_id = ?', [$o->order_id]);
            Alert::success('Заказ #' . $o->order_id . ' объединен с заказом #' . $into->order_id);
            $o->delete();
        }

        $into->rebuildData();
        header('Location: ./?action=show');
        exit;
    }

    public function downloadDoc()
    {
        $o = \App\Extensions\Catalog\Model\Order::findOne(['order_id' => $_REQUEST['order_id']]);
        if (!$o && $_REQUEST['ids']) {
            $ids = explode(',', $_REQUEST['ids']);
            $ids = array_filter($ids, function ($a) {
                return !!(int)$a;
            });

            $o = [];
            foreach ($ids as $id) {
                $oo = new \App\Extensions\Catalog\Model\Order($id);
                if ($oo->status == 'canceled') {
                    Alert::error('Один или несколько заказов находятся в статусе Отменен');
                    header('Location: ./?action=show');
                    exit;
                }

                $o[] = $oo;
            }
        }

        if (!$o) {
            Alert::error('Заказ(ы) не найден(ы)');
            header('Location: ./?action=show');
            exit;
        }

        if (!is_array($o)) {
            $o = [$o];
        }

        $tpl = $_REQUEST['tpl'];

        try {
            $proc = new TemplateProcessor(SF_ROOT_PATH . '/Extensions/Catalog/tpl/docs/word/' . $tpl . '.docx');
            switch ($tpl) {
                case 'schet':
                    $this->downloadDocInvoice($proc, $o);
                    break;
                case 'factura':
                    $this->downloadDocInvoice2($proc, $o);
                    break;
                case 'ttn':
                    $this->downloadDocInvoice3($proc, $o);
                    break;
                default:
                    Alert::error('Шаблон не найден');
                    header('Location: ./?action=form&order_id=' . $o->order_id);
                    exit;
            }

            $name = md5(microtime());
            $proc->saveAs(SF_ROOT_PATH . '/uf/files/' . $name . '.docx');

            header('Content-Disposition: attachment;filename='.$tpl.'_' . implode('_', array_map(function($oo){return $oo->order_id;}, $o)) . '.docx');
            header('Content-Type: application/octet-stream');
            echo file_get_contents(SF_ROOT_PATH . '/uf/files/' . $name . '.docx');
            exit;
        } catch (\Exception $e) {
            Alert::error('Ошибка: ' . $e->getMessage());
            header('Location: ./?action=form&order_id=' . $o->order_id);
            exit;
        }
    }

    protected function downloadDocInvoice3(TemplateProcessor $proc, $os)
    {
        $o = $os[0];
        $u = $o->user;
        $oa = $o->org_active;

        foreach ($os as $o1) {
            if ($o->order_id == $o1->order_id) {
                continue;
            }

            $o->sum_total = $o->__sum_total + $o1->__sum_total;
            $o->sum_actual = $o->__sum_actual + $o1->__sum_actual;
        }

        $pn = 0;
        foreach ($os as $o1) {
            $pn += $o1->getProductCount('COALESCE(is_deleted, 0) = 0');
        }

        $ud = [
            'last_name' => $u->last_name,
            'name' => $u->name,
            'patronym' => $u->patronym,
            'phone' => $u->phone,
        ];

        if ($o->user_last_name || $o->user_name || $o->user_patronym || $o->user_phone) {
            $ud = [
                'last_name' => $o->user_last_name,
                'name' => $o->user_name,
                'patronym' => $o->user_patronym,
                'phone' => $o->user_phone,
            ];
        }

        $proc->setValues([
            'user_last_name' => $oa ? ($o->org_name . ', ИНН ' . $o->org_inn) : ($o->last_name ?? ''),
            'user_name' => $oa ? '' : ($o->name ?? ''),
            'user_patronym' => $oa ? '' : ($o->patronym ?? ''),
            'user_city' => ($o->city ?? '') . ' ' . ($o->address ?? ''),
            'user2_last_name' => $oa ? ($o->org_name . ', ИНН ' . $o->org_inn) : ($ud['last_name'] ?? ''),
            'user2_name' => $oa ? '' : ($ud['name'] ?? ''),
            'user2_patronym' => $oa ? '' : ($ud['patronym'] ?? ''),
            'user2_city' => ($o->city ?? '') . ' ' . ($o->address ?? ''),
            'user2_phone' => $ud['phone'] ?? '',
            'order_id' => $o->order_id,
            'order_date' => Time::normal(time(), false),
            'order_actual' => $o->getTotal3(),
            'order_total' => $o->getTotal2(),
            'order_discount' => $o->discount ?? '0',
            'order_products' => $pn,
            'order_products_spell' => Str::spellNum($pn),
            'order_products_spell_num' => Str::pluralize($pn, 'номер', true, false),
            'order_spelled' => Str::sumToNumber($o->getTotalNum()),
        ]);

        $cloneData = []; $i = 1;
        foreach ($os as $o1) {
            foreach ($o1->getProducts('', '', 'COALESCE(is_deleted, 0) = 0') as $pi) {
                $p = $pi['product'];
                if (isset($cloneData[$p->product_id . '_' . $pi['size']])) {
                    $pi['qty'] += $cloneData[$p->product_id . '_' . $pi['size']]['product_qty'];
                    $i--;
                }

                $cloneData[$p->product_id . '_' . $pi['size']] = [
                    'product_num' => $i++,
                    'product_name' => $p->name,
                    'product_size' => $pi['size'],
                    'product_qty' => $pi['qty'],
                    'product_price' => number_format($pi['price'], 2, '.',' '),
                    'order_price_discount' => ($o->discount ?? 0) ?
                        number_format($pi['price'] - ($pi['price'] * ($o->discount * 0.01)), 2, '.', ' ') :
                        number_format($pi['price'], 2, '.', ' '),
                    'order_sum' => ($o->discount ?? 0) ?
                        number_format(($pi['price'] - ($pi['price'] * ($o->discount * 0.01))) * $pi['qty'], 2, '.', ' ') :
                        number_format($pi['price'] * $pi['qty'], 2, '.', ' '),
                ];
            }
        }

        $proc->cloneRowAndSetValues('product_num', array_values($cloneData));
    }

    protected function downloadDocInvoice2(TemplateProcessor $proc, $os)
    {
        $o = $os[0];
        $u = $o->user;
        $oa = $o->org_active;

        foreach ($os as $o1) {
            if ($o->order_id == $o1->order_id) {
                continue;
            }

            $o->sum_total = $o->__sum_total + $o1->__sum_total;
            $o->sum_actual = $o->__sum_actual + $o1->__sum_actual;
        }

        $pn = 0;
        foreach ($os as $o1) {
            $pn += $o1->getProductCount('COALESCE(is_deleted, 0) = 0');
        }

        $ud = [
            'last_name' => $u->last_name,
            'name' => $u->name,
            'patronym' => $u->patronym,
            'phone' => $u->phone,
        ];

        if ($o->user_last_name || $o->user_name || $o->user_patronym || $o->user_phone) {
            $ud = [
                'last_name' => $o->user_last_name,
                'name' => $o->user_name,
                'patronym' => $o->user_patronym,
                'phone' => $o->user_phone,
            ];
        }

        $proc->setValues([
            'user_last_name' => $oa ? ($o->org_name . ', ИНН ' . $o->org_inn) : ($o->last_name ?? ''),
            'user_name' => $oa ? '' : ($o->name ?? ''),
            'user_patronym' => $oa ? '' : ($o->patronym ?? ''),
            'user_city' => $o->city ?? '',
            'user_address' => $o->address ?? '',
            'user2_last_name' => $oa ? ($o->org_name . ', ИНН ' . $o->org_inn) : ($ud['last_name'] ?? ''),
            'user2_name' => $oa ? '' : ($ud['name'] ?? ''),
            'user2_patronym' => $oa ? '' : ($ud['patronym'] ?? ''),
            'order_date' => Time::normal(time(), false),
            'order_actual' => $o->getTotal3(),
            'order_total' => $o->getTotal2(),
            'order_discount' => $o->discount ?? '0',
            'order_products' => $pn,
            'order_spelled' => Str::sumToNumber($o->getTotalNum()),
        ]);

        $cloneData = [];
        foreach ($os as $o1) {
            foreach ($o1->getProducts('', '', 'COALESCE(is_deleted, 0) = 0') as $pi) {
                $p = $pi['product'];
                if (isset($cloneData[$p->product_id . '_' . $pi['size']])) {
                    $pi['qty'] += $cloneData[$p->product_id . '_' . $pi['size']]['product_qty'];
                }

                $cloneData[$p->product_id . '_' . $pi['size']] = [
                    'product_name' => $p->name,
                    'product_size' => $pi['size'],
                    'product_qty' => $pi['qty'],
                    'product_price' => number_format($pi['price'], 2, '.', ' '),
                    'order_price_discount' => ($o->discount ?? 0) ?
                        number_format($pi['price'] - ($pi['price'] * ($o->discount * 0.01)), 2, '.', ' ') :
                        number_format($pi['price'], 2, '.', ' '),
                    'order_sum' => ($o->discount ?? 0) ?
                        number_format(($pi['price'] - ($pi['price'] * ($o->discount * 0.01))) * $pi['qty'], 2, '.', ' ') :
                        number_format($pi['price'] * $pi['qty'], 2, '.', ' '),
                ];
            }
        }

        $proc->cloneRowAndSetValues('product_name', array_values($cloneData));
    }

    protected function downloadDocInvoice(TemplateProcessor $proc, $os)
    {
        $o = $os[0];
        $u = $o->user;
        $oa = $o->org_active;

        foreach ($os as $o1) {
            if ($o->order_id == $o1->order_id) {
                continue;
            }

            $o->sum_total = $o->__sum_total + $o1->__sum_total;
            $o->sum_actual = $o->__sum_actual + $o1->__sum_actual;
        }

        $pn = 0;
        foreach ($os as $o1) {
            $pn += $o1->getProductCount('COALESCE(is_deleted, 0) = 0');
        }

        $ud = [
            'last_name' => $u->last_name,
            'name' => $u->name,
            'patronym' => $u->patronym,
            'phone' => $u->phone,
        ];

        if ($o->user_last_name || $o->user_name || $o->user_patronym || $o->user_phone) {
            $ud = [
                'last_name' => $o->user_last_name,
                'name' => $o->user_name,
                'patronym' => $o->user_patronym,
                'phone' => $o->user_phone,
            ];
        }

        $proc->setValues([
            'user_last_name' => $oa ? ($o->org_name . ', ИНН ' . $o->org_inn) : ($ud['last_name'] ?? ''),
            'user_name' => $oa ? '' : ($ud['name'] ?? ''),
            'user_patronym' => $oa ? '' : ($ud['patronym'] ?? ''),
            'user_phone' => ($ud['phone'] ?? ''),
            'user_email' => $u->email ?? '',
            'user_city' => ($o->city ?? '') . ' ' . ($o->address ?? ''),
            'order_id' => implode(', ', array_map(function ($oo) { return $oo->order_id; }, $os)),
            'order_mul' => count($os) > 1 ? 'ы' : '',
            'order_date' => Time::normal(time(), false),
            'order_actual' => $o->getTotal3(),
            'order_total' => $o->getTotal2(),
            'order_discount' => $o->discount ?? '0',
            'order_products' => $pn,
            'order_spelled' => Str::sumToNumber($o->getTotalNum()),
        ]);

        $cloneData = []; $i = 1;
        foreach ($os as $o1) {
            foreach ($o1->getProducts('', '', 'COALESCE(is_deleted, 0) = 0') as $pi) {
                $p = $pi['product'];
                if (isset($cloneData[$p->product_id . '_' . $pi['size']])) {
                    $pi['qty'] += $cloneData[$p->product_id . '_' . $pi['size']]['product_qty'];
                    $i--;
                }

                $cloneData[$p->product_id . '_' . $pi['size']] = [
                    'product_num' => $i++,
                    'product_name' => $p->name,
                    'product_size' => $pi['size'],
                    'product_qty' => $pi['qty'],
                    'product_price' => number_format($pi['price'], 2, '.', ' '),
                    'order_price_discount' => ($o->discount ?? 0) ?
                        number_format($pi['price'] - ($pi['price'] * ($o->discount * 0.01)), 2, '.', ' ') :
                        number_format($pi['price'], 2, '.', ' '),
                    'order_sum' => ($o->discount ?? 0) ?
                        number_format(($pi['price'] - ($pi['price'] * ($o->discount * 0.01))) * $pi['qty'], 2, '.', ' ') :
                        number_format($pi['price'] * $pi['qty'], 2, '.', ' '),
                ];
            }
        }

        $proc->cloneRowAndSetValues('product_num', array_values($cloneData));
    }

    public function renderDoc()
    {
        $id = (int)$_REQUEST['order_id'];
        $o = \App\Extensions\Catalog\Model\Order::findOne(['order_id' => $_REQUEST['order_id']]);
        $tpl = $_REQUEST['tpl'];
        $print = (int)$_REQUEST['print'];
        $sub = (int)($_REQUEST['sub'] ?? 0);

        if ($sub) {
            DB::query('insert into catalog_order_sub_dl (order_id, sub_num) values (?, ?)', [$id, $sub]);
        }

        ob_start();
        include SF_ROOT_PATH . '/Extensions/Catalog/tpl/docs/' . $tpl . '.tpl';
        $ret = ob_get_clean();

        if (!$print) {
            $pdf = new Dompdf([
                'defaultFont' => 'DejaVu Serif'
            ]);
            $pdf->loadHtml($ret);

            if ($tpl == 'factura' || $tpl == 'ttn') {
                $pdf->setPaper('A4', 'landscape');
            }

            $pdf->render();
            $pdf->stream();
            exit;
        } else {
            exit($ret);
        }
    }

    public function resendMail()
    {
        $o = \App\Extensions\Catalog\Model\Order::findOne(['order_id' => $_REQUEST['order_id']]);
        $u = User::findOne(['user_id' => $o->user_id]);

        $to = $_REQUEST['to'] == 'admin' ? Core::siteParam('form_email') : $u->email;

        // send mail
        (new MailAssist($to, 'Заказ №' . $o->order_id . ' обновлен'))
            ->tpl('email_order_' . $o->status . '.tpl', ['order' => $o, 'user' => $u])
            ->send();

        Alert::success('Сообщение успешно отправлено');
        $this->form();
    }

    protected function changeENUM($withRedirect = true)
    {
        // NOTE: won't work on other enums.

        $oldstatus = DB::result('select status from catalog_order where order_id = ?', 0, [(int)$_REQUEST[$this->pk->name]]);

        $newStatus = -1;
        if ($oldstatus != $_REQUEST['newstatus']) {
            $newStatus = $this->statusToNumMap[$_REQUEST['newstatus']];
        }

        parent::changeENUM(false);

        $_REQUEST['status'] = $_REQUEST['newstatus'];
        $this->updateStatus($newStatus, $_REQUEST['order_id']);

        exit(json_encode(['success' => true]));
    }

    protected function updateStatus($newStatus, $ret)
    {
        $o = \App\Extensions\Catalog\Model\Order::findOne(['order_id' => $ret]);
        $o->rebuildData();

        if ($newStatus >= 0) {
            DB::query('update catalog_order set status_num = ?, edited_on = ? where order_id = ?', [$newStatus, time(), $_REQUEST[$this->pk->name]]);
            $u = User::findOne(['user_id' => $o->user_id]);

            // send mail
            (new MailAssist($u->email, 'Заказ №' . $o->order_id . ' обновлен'))
                ->alsoTo(Core::siteParam('form_email'))
                ->tpl('email_order_' . $_REQUEST['status'] . '.tpl', ['order' => $o, 'user' => $u])
                ->send();

            if ($_REQUEST['status'] == 'canceled') {
                // revert shit
                foreach ($o->getProducts('', '', 'COALESCE(is_deleted, 0) = 0') as $p) {
                    $prod = $p['product'];
                    $prod->stock += $p['qty'];

                    $data2 = json_decode($prod->size, true) ?? [];
                    if ($data2) {
                        foreach ($data2['v'] as &$sz) {
                            if ($sz['size'] == $p['size']) {
                                $sz['stock'] += $p['qty'];
                            }
                        }
                    }

                    $prod->size = json_encode($data2, JSON_UNESCAPED_UNICODE);
                    $prod->recalcStock();
                    $prod->save();
                    $prod->reload();
                }

                $o->rebuildData();
            }
        }
    }

    public function save()
    {
        $newStatus = -1;
        if ($this->row['status'] != $_REQUEST['status']) {
            $newStatus = $this->statusToNumMap[$_REQUEST['status']];
        }

        $ret = parent::save();

        $this->updateStatus($newStatus, $ret);
        return $ret;
    }

    protected function initTable()
    {
        parent::initTable();

        if (isset($_REQUEST[$this->pk->name])) {
            $tbl = $this->fields['products'];
            $tbl->cols = [
                [
                    'n' => '__id',
                    't' => '',
                    'l' => '',
                    'v' => '__NO_EDIT__',
                    'e' => '',
                ],
                [
                    'n' => '__deleted',
                    't' => '',
                    'l' => '',
                    'v' => '__NO_EDIT__',
                    'e' => '',
                ],
                [
                    'n' => '__rm_id',
                    't' => '',
                    'l' => '',
                    'v' => '__NO_EDIT__',
                    'e' => '',
                ],
                [
                    'n' => 'row_num',
                    't' => 'text',
                    'l' => '№',
                    'v' => '__NO_EDIT__',
                    'e' => '',
                ],
                [
                    'n' => 'product',
                    't' => 'combo',
                    'l' => 'Продукт',
                    'v' => '',
                    'e' => 'AJAX:searchProducts',
                    'c' => 'vtProdUpdateSizes(this)'
                ],
                [
                    'n' => 'img',
                    't' => 'image',
                    'l' => 'Фото',
                    'v' => '__NO_EDIT__',
                    'e' => '',
                ],
                [
                    'n' => 'size',
                    't' => 'combo',
                    'l' => 'Размер',
                    'v' => '',
                    'e' => '',
                ],
                [
                    'n' => 'qty',
                    't' => 'text',
                    'l' => 'Количество',
                    'v' => '',
                    'e' => '',
                ],
                [
                    'n' => 'price',
                    't' => 'text',
                    'l' => 'Цена',
                    'v' => '',
                    'e' => '',
                ],
                [
                    'n' => 'price_old',
                    't' => 'text',
                    'l' => 'Старая цена',
                    'v' => '',
                    'e' => '',
                ],
                [
                    'n' => 'sum',
                    't' => 'text',
                    'l' => 'Сумма',
                    'v' => '__NO_EDIT__',
                    'e' => '',
                ],
            ];

            $page = (int)($_REQUEST['page'] ?? 0) * 50;
            $id = DB::escape($_REQUEST[$this->pk->name]);
            $tbl->query = <<<SQL
select 
    o.order_product_id as __id,
    CONCAT('RM:', p.name, ';;', p.product_id) as product,
    ROW_NUMBER() OVER() as row_num,
    p.photo as img_json,
    o.size,
    o.qty,
    o.price,
    o.price_old,
    o.sum,
    coalesce(o.is_deleted, 0) as __deleted
from catalog_order_product o 
left join catalog_product p on p.product_id = o.product_id
where o.order_id = $id
limit 50 offset $page
SQL;

            $tbl->queryCount = <<<SQL
select 
    count(*)
from catalog_order_product o 
left join catalog_product p on p.product_id = o.product_id
where o.order_id = $id
SQL;

            $link = Container::getRequest()->getPath();
            $tbl->customjs = <<<JS
<script>
function vtProdUpdateSizes(i) {
    const item = document.querySelector('#cb-size .form-control__dropdown-list');
    item.innerHTML = '';
    
    $.ajax({
        url: `$link?action=getSizes&id=\${i.value}`,
        success: (data) => {
            item.innerHTML = data;
        }
    })
}
</script>
JS;

        }

        $this->p_on = 50;
    }

    protected function deleteItem($id)
    {
        $o = \App\Extensions\Catalog\Model\Order::findOne(['order_id' => $id]);

        // revert shit
        foreach ($o->getProducts('', '', 'COALESCE(is_deleted, 0) = 0') as $p) {
            $prod = $p['product'];
            if (!($prod instanceof \App\Extensions\Catalog\Model\Product)) {
                continue;
            }

            $prod->stock += $p['qty'];

            $data2 = json_decode($prod->size, true) ?? [];
            if ($data2) {
                foreach ($data2['v'] as &$sz) {
                    if ($sz['size'] == $p['size']) {
                        $sz['stock'] += $p['qty'];
                    }
                }
            }

            $prod->size = json_encode($data2, JSON_UNESCAPED_UNICODE);
            $prod->save();
            $prod->reload();
        }

        DB::query('delete from catalog_order_product_sub where order_id = ?', [$id]);
        DB::query('delete from catalog_order_product where order_id = ?', [$id]);
        return parent::deleteItem($id);
    }

    protected function formBeforeOutput(&$row)
    {
        $id = $_REQUEST[$this->pk->name];
        $ord = new \App\Extensions\Catalog\Model\Order($id);
        $u = $ord->user;

        $row['user_name'] = $row['user_name'] ?: $u->name;
        $row['user_last_name'] = $row['user_last_name'] ?: $u->last_name;
        $row['user_patronym'] = $row['user_patronym'] ?: $u->patronym;
        $row['user_phone'] = $row['user_phone'] ?: $u->phone;
    }

    public function form()
    {
        $this->title = 'Редактировать заказ №' . $_REQUEST[$this->pk->name];

        $id = $_REQUEST[$this->pk->name];
        $ord = new \App\Extensions\Catalog\Model\Order($id);
        $this->fields['total']->value = $ord->getTotal2();
        $u = $ord->user;

        $subs = '';
        if (DB::result('select count(*) from catalog_order_product_sub where order_id = ?', 0, [$id])) {
            $subs = <<<HTML
<div class="data3">
    <div class="data3__head">
        <div class="data3__title">Дозаказы</div>
    </div>
    <div class="data3__content">
        
HTML;

            $ret = DB::assoc('select distinct sub_num from catalog_order_product_sub where order_id = ? order by sub_num desc', false, false, [$id]);
            foreach ($ret as $k=>$s) {
                $subs .= '<a class="BtnSecondaryMonoSm" target="_blank" href="./?action=renderDoc&order_id=' . $id .
                    '&tpl=zakaz&print=1&sub='. $s['sub_num'] .'">';
                $subs .= 'Распечатать дозаказ №' . $s['sub_num'];
                $subs .= '</a>';
            }

            $subs .= <<<HTML
    </div>
</div>
HTML;

        }

        $this->extraRight = <<<HTML
$subs
<div class="data3">
    <div class="data3__head">
        <div class="data3__title">Документы</div>
    </div>
    <div class="data3__content">
        <a class="BtnSecondaryMonoSm" target="_blank" href="./?action=renderDoc&order_id=$id&tpl=zakaz&print=1">Распечатать</a>
        <div style="display: flex; flex-direction: row; width: 100%">
            <a style="flex: 1; margin-right: 0.5rem" class="BtnSecondaryMonoSm" target="_blank" href="./?action=renderDoc&order_id=$id&tpl=schet&print=1">Счет</a>
            <a style="flex: 1" class="BtnPrimarySm" target="_blank" href="./?action=downloadDoc&order_id=$id&tpl=schet">Скачать .docx</a>
        </div>
        <div style="display: flex; flex-direction: row; width: 100%">
            <a style="flex: 1; margin-right: 0.5rem" class="BtnSecondaryMonoSm" target="_blank" href="./?action=renderDoc&order_id=$id&tpl=factura&print=1">Счет-фактура</a>
            <a style="flex: 1" class="BtnPrimarySm" target="_blank" href="./?action=downloadDoc&order_id=$id&tpl=factura">Скачать .docx</a>
        </div>
        <div style="display: flex; flex-direction: row; width: 100%">
            <a style="flex: 1; margin-right: 0.5rem" class="BtnSecondaryMonoSm" target="_blank" href="./?action=renderDoc&order_id=$id&tpl=ttn&print=1">Накладная</a>
            <a style="flex: 1" class="BtnPrimarySm" target="_blank" href="./?action=downloadDoc&order_id=$id&tpl=ttn">Скачать .docx</a>
        </div>
        <a class="BtnSecondaryMonoSm" href="./?action=resendMail&order_id=$id&to=user">Продублировать на почту Клиенту</a>
        <a class="BtnSecondaryMonoSm" href="./?action=resendMail&order_id=$id&to=admin">Продублировать на почту Админу</a>
    </div>
</div>
HTML;

        parent::form();
    }
}