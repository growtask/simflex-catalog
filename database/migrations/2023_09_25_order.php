<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->table('catalog_category', function (Schema\Table $c) {
            $c->enum('banner_style', ['', 'sale', 'banner', 'main']);
        });
    }

    public function down(Schema $s)
    {
    }
};