<?php

namespace App\Extensions\Catalog\Component;

use Simflex\Core\Container;
use Simflex\Core\Core;
use Simflex\Core\DB;
use Simflex\Core\Time;
use Simflex\Extensions\Content\Content;

class Review extends Content
{
    public $rating = 0;
    public $ratings = [
        '1' => 0,
        '2' => 0,
        '3' => 0,
        '4' => 0,
        '5' => 0,
    ];
    public $reviewCount = 0;
    public $pagedReviewCount = 0;
    public $reviews = [];
    public $pics = [];
    public $totalPics = 0;

    protected function content()
    {
        if (($a = ($_REQUEST['action'] ?? '')) && method_exists($this, $a)) {
            $this->{$a}();
        }

        $q = DB::query('select `path` from catalog_upload where upload_id in (select upload_id from catalog_review_image where review_id in (select review_id from catalog_review where is_approved = 1)) order by date desc limit 20');
        while ($r = DB::fetch($q)) {
            $this->pics[] = $r['path'];
        }

        $this->totalPics = DB::result('select count(*) from catalog_upload where upload_id in (select upload_id from catalog_review_image where review_id in (select review_id from catalog_review where is_approved = 1))', 0);

        $this->reviewCount = (new DB\AQ())->from('catalog_review')->select('count(*)')->where(
            'is_approved = 1'
        )->fetchScalar() ?? 0;
        $this->rating = (new DB\AQ())->from('catalog_review')->select('avg(rating)')->fetchScalar() ?? 0;

        for ($i = 1; $i <= 5; ++$i) {
            $this->ratings[(string)$i] = (new DB\AQ())->from('catalog_review')->select('count(*)')->where([
                'rating' => $i,
                'is_approved' => 1,
            ])->fetchScalar() ?? 0;
        }

        $qc = \App\Extensions\Catalog\Model\Review::findAdv()
            ->where('is_approved = 1')
            ->select('count(*)');

        $q = \App\Extensions\Catalog\Model\Review::findAdv()
            ->where('is_approved = 1')
            ->limit('5 offset ' . ($_REQUEST['page'] ?? 0) * 5);

        if ($sort = ($_REQUEST['sort'] ?? 'new')) {
            $sReal = '';
            switch ($sort) {
                case 'high':
                    $sReal = 'rating desc'; break;
                case 'low':
                    $sReal = 'rating asc'; break;
                case 'new':
                    $sReal = 'date desc'; break;
                case 'old':
                    $sReal = 'date asc'; break;
                case 'useful':
                    $sReal = 'likes desc'; break;
            }

            $q = $q->orderBy($sReal);
        }

        if ($filter = ($_REQUEST['filter'] ?? [])) {
            $where = implode(' or ', array_map(function ($i) {
                return 'rating = ' . DB::escape($i);
            }, $filter));

            $qc = $qc->andWhere($where);
            $q = $q->andWhere($where);
        }

        $this->pagedReviewCount = $qc->fetchScalar() ?? 0;
        $this->reviews = $q->all();

        parent::content();
    }

    protected function images()
    {
        $r = Container::getRequest();
        $out = [
            'total' => DB::result('select count(*) from catalog_upload where upload_id in (select upload_id from catalog_review_image where review_id in (select review_id from catalog_review where is_approved = 1))', 0),
            'items' => [],
        ];

        $q = DB::query('select `path` from catalog_upload where upload_id in (select upload_id from catalog_review_image where review_id in (select review_id from catalog_review where is_approved = 1)) order by date desc limit 1 offset ' . $r->request('page', 0));
        while ($r = DB::fetch($q)) {
            $out['items'][] = $r['path'];
        }

        exit(json_encode($out));
    }

    protected function make()
    {
        $r = Container::getRequest();
        if (!$r->request('name') || !$r->request('rating')) {
            exit(json_encode(['success' => false]));
        }

        $rv = new \App\Extensions\Catalog\Model\Review();
        $rv->name = $r->request('name');
        $rv->email = $r->request('email', '');
        $rv->is_approved = 0;
        $rv->title = $r->request('title');
        $rv->pros = $r->request('pros');
        $rv->cons = $r->request('cons');
        $rv->comment = $r->request('comment');
        $rv->response = '';
        $rv->date = Time::create()->asMySQL();
        $rv->rating = $r->request('rating');
        $rv->likes = 0;
        $rv->dislikes = 0;
        $rv->save();
        $rv->reload();

        if ($r->request('photos')) {
            foreach ($r->request('photos') as $pid) {
                DB::query('insert into catalog_review_image (upload_id, review_id) values (?, ?)', [$pid, $rv->review_id]);
            }
        }

        $this->sendTelegram($rv->name . ', ' . $rv->email, $rv->review_id, $rv->rating, $rv->comment);
        exit(json_encode(['success' => true]));
    }

    protected function sendTelegram($user, $reviewId, $rate, $comment)
    {
        $patch = function ($i) {
            return str_replace(
                ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'],
                ['\\_', '\\*', '\\[', '\\]', '\\(', '\\)', '\\~', '\\`', '\\>', '\\#', '\\+', '\\-', '\\=', '\\|', '\\{', '\\}', '\\.', '\\!'],
                $i);
        };

        $md = <<<MD
**НОВЫЙ ОТЗЫВ НА САЙТЕ НЕПОСЕДА**

**Клиент: ** {$patch($user)}
**ID отзыва: ** {$patch($reviewId)}
**Рейтинг: ** {$patch($rate)}
**Комментарий: ** {$patch($comment)}

[Посмотреть отзыв в админке]({$patch(url('/admin/shop/review/?action=form&review_id=' . $reviewId))})
MD;

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
        return true;
    }

    protected function vote()
    {
        if (!($id = $_REQUEST['id'] ?? false) || !($ud = $_REQUEST['vote'] ?? false) || !($inv = $_REQUEST['inv'] ?? 'false')) {
            exit(json_encode(['success' => false]));
        }

        $r = \App\Extensions\Catalog\Model\Review::findOne(['review_id' => $id]);
        if (!$r) {
            exit(json_encode(['success' => false]));
        }

        $inv = $inv == 'true';
        $ud = (int)$ud;

        if ($ud > 0) {
            if ($inv) {
                $r->likes = (int)$r->likes - 1;
            } else {
                $r->likes = (int)$r->likes + 1;
            }
        } else {
            if ($inv) {
                $r->dislikes = (int)$r->dislikes - 1;
            } else {
                $r->dislikes = (int)$r->dislikes + 1;
            }
        }

        if ((int)$r->likes < 0) {
            $r->likes = 0;
        }

        if ((int)$r->dislikes < 0) {
            $r->dislikes = 0;
        }

        $r->save();
        exit(json_encode(['success' => true]));
    }

    protected function uploadImage()
    {
        if (!isset($_FILES['img']) || $_FILES['img']['error']) {
            exit(json_encode(['id' => 0]));
        }

        if (!is_dir(SF_ROOT_PATH . '/uf/reviews')) {
            mkdir(SF_ROOT_PATH . '/uf/reviews');
        }

        $ext = explode('.', $_FILES['img']['name']);

        $path = '/uf/reviews/' . md5(microtime()) . '.' . end($ext);
        move_uploaded_file($_FILES['img']['tmp_name'], SF_ROOT_PATH . $path);

        DB::query('insert into catalog_upload (filename, path, date) values (?, ?, CURRENT_TIMESTAMP())', [
            $_FILES['img']['name'],
            $path
        ]);

        exit(json_encode(['id' => DB::insertId()]));
    }
}