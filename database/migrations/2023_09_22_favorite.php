<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_fav', function (Schema\Table $c) {
            $c->id('fav_id');
            $c->integer('user_id')->setNull()->foreignKey('user');
            $c->string('sess_id')->setNull();
        });

        $s->createTable('catalog_fav_product', function (Schema\Table $c) {
            $c->id('fav_product_id');
            $c->integer('fav_id')->foreignKey('catalog_fav');
            $c->integer('product_id')->foreignKey('catalog_product');
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_fav_product');
        $s->dropTable('catalog_fav');
    }
};