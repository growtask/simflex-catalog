<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->table('catalog_order_product', function (Schema\Table $c) {
            $c->price('price');
            $c->price('price_old');
        });
    }

    public function down(Schema $s)
    {
        $s->table('catalog_order_product', function (Schema\Table $c) {
            $c->dropColumns(['price', 'price_old']);
        });
    }
};