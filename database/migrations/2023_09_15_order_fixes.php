<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->table('catalog_order_product', function (Schema\Table $c) {
            $c->boolean('is_deleted')->setDefault(0);
            $c->boolean('is_changed')->setDefault(0);
            $c->boolean('is_added')->setDefault(0);
        });
    }

    public function down(Schema $s)
    {
        $s->table('catalog_order_product', function (Schema\Table $c) {
            $c->dropColumns(['is_deleted', 'is_changed', 'is_added']);
        });
    }
};