<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_order_sub_dl', function (Schema\Table $c) {
            $c->id('dl_id');
            $c->integer('order_id');
            $c->integer('sub_num');
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_order_sub_dl');
    }
};