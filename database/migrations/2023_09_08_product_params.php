<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_product_param', function (Schema\Table $c) {
            $c->id('param_id');
            $c->boolean('is_active')->setDefault(1);
            $c->integer('npp')->setDefault(0);
            $c->string('key');
            $c->string('name');
        });

        $s->createTable('catalog_product_param_value', function (Schema\Table $c) {
            $c->id('value_id');
            $c->integer('product_id')->foreignKey('catalog_product');
            $c->integer('param_id')->foreignKey('catalog_product_param');
            $c->text('value');
        });

        $s->createTable('catalog_product_param_cat', function (Schema\Table $c) {
            $c->id('p2c_id');
            $c->integer('param_id')->foreignKey('catalog_product_param');
            $c->integer('category_id')->foreignKey('catalog_category');
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_product_param_cat');
        $s->dropTable('catalog_product_param_value');
        $s->dropTable('catalog_product_param');
    }
};