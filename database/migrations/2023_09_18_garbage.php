<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->table('catalog_order', function (Schema\Table $c) {
            $c->integer('status_num');
        });
    }

    public function down(Schema $s)
    {
        $s->table('catalog_order', function (Schema\Table $c) {
            $c->dropColumns('status_num');
        });
    }
};