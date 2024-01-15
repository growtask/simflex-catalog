<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_transcomp', function (Schema\Table $c) {
            $c->id('transcomp_id');
            $c->integer('npp')->setDefault(0);
            $c->string('name');
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_transcomp');
    }
};