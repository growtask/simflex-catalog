<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_sale', function (Schema\Table $c) {
            $c->id('sale_id');
            $c->string('name');
            $c->addColumn('start', 'datetime');
            $c->addColumn('end', 'datetime');
            $c->integer('discount');
            $c->boolean('is_running')->setDefault(0);
        });

        $s->createTable('catalog_sale_category', function (Schema\Table $c) {
            $c->id('sale_category_id');
            $c->integer('sale_id')->foreignKey('catalog_sale');
            $c->integer('category_id')->foreignKey('catalog_category');
        });

        $s->table('catalog_product', function (Schema\Table $c) {
            $c->price('bk_price');
            $c->price('bk_price_old');
            $c->boolean('bk_enable');
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_sale_category');
        $s->dropTable('catalog_sale');
        $s->table('catalog_product', function (Schema\Table $c) {
            $c->dropColumns(['bk_price', 'bk_price_old', 'bk_enable']);
        });
    }
};