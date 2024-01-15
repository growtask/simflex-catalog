<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->table('catalog_order', function (Schema\Table $c) {
            $c->integer('edited_on');
        });
    }

    public function down(Schema $s)
    {
        $s->table('catalog_order', function (Schema\Table $c) {
            $c->dropColumns(['edited_on']);
        });
    }
};