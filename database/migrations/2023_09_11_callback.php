<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('callback', function (Schema\Table $c) {
            $c->id('callback_id');
            $c->string('type');
            $c->string('name');
            $c->string('email');
            $c->string('phone');
            $c->text('message');
            $c->addColumn('date', 'datetime');
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('callback');
    }
};