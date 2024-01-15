<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_category_cache', function (Schema\Table $c) {
            $c->id('cache_id');
            $c->integer('category_id')->foreignKey('catalog_category');
            $c->text('filters');
            $c->text('params');
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_category_cache');
    }
};