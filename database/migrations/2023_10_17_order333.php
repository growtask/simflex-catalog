<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->table('catalog_order', function (Schema\Table $c) {
            $c->enum('status', ['new', 'accepted', 'ready', 'added', 'sent', 'delivered', 'finished', 'canceled']);
        });

        $res = \Simflex\Core\DB::result('select params from struct_data where id = 446', 0);
        $res = unserialize($res);
        $res['main']['enum'] = 'new=Новый;;accepted=Принят;;ready=Собран;;added=Дозаказ;;sent=Отправлен;;delivered=Доставлен;;finished=Завершен;;canceled=Отменен';
        $res = serialize($res);
        \Simflex\Core\DB::query('update struct_data set params = ? where id = 446', [$res]);
    }

    public function down(Schema $s)
    {
    }
};