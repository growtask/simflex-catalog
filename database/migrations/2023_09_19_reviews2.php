<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->table('catalog_upload', function (Schema\Table $c) {
            $c->addColumn('date', 'datetime');
        });

        $s->table('catalog_review', function (Schema\Table $c) {
            $c->integer('likes')->setDefault(0);
            $c->integer('dislikes')->setDefault(0);
        });

        $s->table('catalog_review_image', function (Schema\Table $c) {
            $c->integer('review_id')->foreignKey('catalog_review');
        });
    }

    public function down(Schema $s)
    {
        $s->table('catalog_upload', function (Schema\Table $c) {
            $c->dropColumns('date');
        });

        $s->table('catalog_review', function (Schema\Table $c) {
            $c->dropColumns(['likes', 'dislikes']);
        });

        $s->table('catalog_review_image', function (Schema\Table $c) {
            $c->dropColumns('review_id');
        });
    }
};