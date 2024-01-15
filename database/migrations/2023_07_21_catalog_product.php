<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_product', function (Schema\Table $c) {
            $c->id('product_id');
            $c->boolean('is_active');
            $c->integer('npp');
            $c->string('alias');
            $c->string('path');
            $c->string('name');
            $c->text('desc');
            $c->text('photo');
            $c->string('meta_title')->setNull();
            $c->text('meta_desc')->setNull();
            $c->text('meta_keywords')->setNull();
            $c->boolean('is_new')->setDefault(0);
            $c->integer('new_timeout')->setDefault(0);
            $c->boolean('is_popular')->setDefault(0);
            $c->text('seo')->setNull();
            $c->string('sku');
            $c->price('price');
            $c->price('price_old')->setDefault(0);
            $c->price('price_base')->setDefault(0);
            $c->integer('stock')->setDefault(0);
        });

        $s->createTable('catalog_p2c', function (Schema\Table $c) {
            $c->id('p2c_id');
            $c->integer('category_id');
            $c->integer('product_id');
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_p2c');
        $s->dropTable('catalog_product');
    }
};