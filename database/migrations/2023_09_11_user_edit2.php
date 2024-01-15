<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->table('user', function (Schema\Table $c) {
            $c->string('phone')->setDefault('');
        });
    }

    public function down(Schema $s)
    {
        $s->table('user', function (Schema\Table $c) {
            $c->dropColumns('phone');
        });
    }
};