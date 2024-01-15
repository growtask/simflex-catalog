<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_category_count', function (Schema\Table $c) {
            $c->id('ccc_id');
            $c->integer('category_id')->foreignKey('catalog_category');
            $c->integer('count');
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_category_count');
    }
};