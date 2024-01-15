<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_order', function (Schema\Table $c) {
            $c->id('order_id');
            $c->integer('user_id')->foreignKey('user');
            $c->addColumn('date', 'datetime');
            $c->enum('status', ['new', 'accepted', 'sent', 'delivered', 'finished', 'canceled'])->setDefault('new');
            $c->string('name');
            $c->string('last_name');
            $c->string('patronym');
            $c->string('transcomp');
            $c->string('city');
            $c->string('address');
            $c->string('phone');
            $c->string('email');
            $c->text('comment');
            $c->string('tracking');
            $c->price('sum_total');
            $c->price('sum_actual');
            $c->integer('qty');
        });

        $s->createTable('catalog_order_product', function (Schema\Table $c) {
            $c->id('order_product_id');
            $c->integer('order_id')->foreignKey('catalog_order');
            $c->integer('product_id')->foreignKey('catalog_product');
            $c->integer('qty');
            $c->price('sum');
            $c->string('size');
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_order_product');
        $s->dropTable('catalog_order');
    }
};