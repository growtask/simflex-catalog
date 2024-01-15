<?php
namespace App\Extensions\Catalog\Component;

use App\Extensions\Catalog\SessionAssist;
use Simflex\Core\ComponentBase;

class Fav extends ComponentBase
{
    protected function content()
    {
        if (!method_exists($this, $_REQUEST['action'])) {
            exit(json_encode(['success' => false]));
        }

        exit(json_encode($this->{$_REQUEST['action']}()));
    }

    protected function add()
    {
        if (SessionAssist::$fav->inFav($_REQUEST['id'])) {
            return ['success' => false];
        }

        return ['success' => SessionAssist::$fav->addProduct($_REQUEST['id'])];
    }

    protected function remove()
    {
        return ['success' => SessionAssist::$fav->removeProduct($_REQUEST['id'])];
    }

    protected function inFav()
    {
        if (!is_array($_REQUEST['ids'])) {
            return ['result' => []];
        }

        $out = [];
        foreach ($_REQUEST['ids'] as $id) {
            if (!(int)$id) {
                $out[] = ['id' => $id, 'result' => false];
                continue;
            }

            $out[] = [
                'id' => $id,
                'result' => SessionAssist::$fav->inFav($id)
            ];
        }

        return ['result' => json_encode($out)];
    }
}