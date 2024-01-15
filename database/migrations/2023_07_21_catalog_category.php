<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->createTable('catalog_category', function (Schema\Table $c) {
            $c->id('category_id');
            $c->boolean('is_active');
            $c->integer('pid');
            $c->integer('npp');
            $c->string('name'); // title
            $c->string('name_2')->setNull(); // card title
            $c->text('short');
            $c->string('alias');
            $c->string('path');
            $c->string('photo')->setNull(); // preview
            $c->string('photo_large')->setNull(); // preview large
            $c->string('banner_title')->setNull();
            $c->text('banner_desc')->setNull();
            $c->boolean('banner_enable')->setNull();
            $c->boolean('is_sale');
            $c->text('seo')->setNull();
        });
    }

    public function down(Schema $s)
    {
        $s->dropTable('catalog_category');
    }
};