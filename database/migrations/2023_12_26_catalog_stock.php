<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_stock', function (Schema\Table $c) {
            $c->id('stock_id');
            $c->integer('product_id')->foreignKey('catalog_product');
            $c->boolean('is_active');
            $c->integer('available');
            $c->integer('in_orders');
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_stock');
    }
};