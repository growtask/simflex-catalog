<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_p2p_cache', function (Schema\Table $c) {
            $c->id('cache_id');
            $c->integer('source_id')->foreignKey('catalog_product', 'product_id');
            $c->integer('product_id')->foreignKey('catalog_product');
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_p2p_cache');
    }
};