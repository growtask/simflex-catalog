<?php
namespace App\Extensions\Catalog\Admin;

use Simflex\Admin\Base;
use Simflex\Core\DB;

class Review extends Base
{
    public function vtAdd()
    {
        $id = (int)$_REQUEST[$this->pk->name];
        $img = $_REQUEST['img'];

        DB::query('insert into catalog_upload (filename, path, date) values (\'\', ?, CURRENT_TIMESTAMP())', [
            $img
        ]);

        DB::query('insert into catalog_review_image (upload_id, review_id) values (?, ?)', [
            DB::insertId(), $id
        ]);

        exit(json_encode(['success' => true]));
    }

    public function vtEdit()
    {
        $id = (int)$_REQUEST['id'];
        $img = $_REQUEST['img'];

        DB::query('update catalog_upload set path = ? where upload_id = (select upload_id from catalog_review_image where review_image_id = ? limit 1)', [
            $img, $id
        ]);

        exit(json_encode(['success' => true]));
    }

    public function vtGet()
    {
        exit(json_encode([
            'total' => $this->fields['images']->getCount(),
            'v' => $this->fields['images']->getValue()
        ], JSON_UNESCAPED_UNICODE));
    }

    public function vtDelete()
    {
        $id = (int)$_REQUEST['id'];
        DB::query('delete from catalog_review_image where review_image_id = ?', [$id]);
        exit(json_encode(['success' => true]));
    }

    protected function initTable()
    {
        parent::initTable();

        $id = DB::escape($_REQUEST[$this->pk->name] ?? '');
        if ($id) {
            $page = (int)($_REQUEST['page'] ?? 0) * 10;

            $tbl = $this->fields['images'];
            $tbl->query = <<<SQL
select
    c.review_image_id as __id,
    u.path as img
from catalog_review_image c
left join catalog_upload u on c.upload_id = u.upload_id
where c.review_id = $id
limit 10 offset $page
SQL;

            $tbl->queryCount = <<<SQL
select
    count(*)
from catalog_review_image c
left join catalog_upload u on c.upload_id = u.upload_id
where c.review_id = $id
SQL;


            $tbl->cols = [
                [
                    'n' => '__id',
                    't' => '',
                    'l' => '',
                    'v' => '__NO_EDIT__',
                    'e' => '',
                ],
                [
                    'n' => 'img',
                    't' => 'image',
                    'l' => 'Фото',
                    'v' => '',
                    'e' => '',
                ],
            ];
        }
    }

    public function form()
    {
        parent::form();
    }
}