<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_p2p', function (Schema\Table $c) {
            $c->id('p2p_id');
            $c->integer('left_id');
            $c->integer('right_id');
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_p2p');
    }
};