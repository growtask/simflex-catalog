<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_review', function (Schema\Table $c) {
            $c->id('review_id');
            $c->string('name');
            $c->string('email');
            $c->boolean('is_approved');
            $c->string('title');
            $c->text('pros');
            $c->text('cons');
            $c->text('comment');
            $c->text('response');
            $c->addColumn('date', 'datetime');
            $c->integer('rating');
        });

        $s->createTable('catalog_upload', function (Schema\Table $c) {
            $c->id('upload_id');
            $c->string('filename');
            $c->string('path');
        });

        $s->createTable('catalog_review_image', function (Schema\Table $c) {
            $c->id('review_image_id');
            $c->integer('upload_id')->foreignKey('catalog_upload');
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_review_image');
        $s->dropTable('catalog_upload');
        $s->dropTable('catalog_review');
    }
};