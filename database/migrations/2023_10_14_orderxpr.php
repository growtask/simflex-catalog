<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_order_product_sub', function (Schema\Table $c) {
            $c->id('ops_id');
            $c->integer('order_id')->foreignKey('catalog_order');
            $c->integer('product_id')->foreignKey('catalog_product');
            $c->integer('qty');
            $c->string('size');
            $c->price('sum');
            $c->integer('sub_num')->setDefault(0);
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_order_product_sub');
    }
};