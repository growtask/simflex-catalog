<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->table('catalog_sale', function (Schema\Table $c) {
            $c->boolean('is_active')->setDefault(0);
        });
    }

    public function down(Schema $s)
    {
        $s->table('catalog_sale', function (Schema\Table $c) {
            $c->dropColumns('is_active');
        });
    }
};