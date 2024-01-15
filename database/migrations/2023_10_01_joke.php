<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->table('catalog_order_product', function (Schema\Table $c) {
            $c->integer('stock')->setDefault(0);
        });
    }

    public function down(Schema $s)
    {
        $s->table('catalog_order_product', function (Schema\Table $c) {
            $c->dropColumns('stock');
        });
    }
};