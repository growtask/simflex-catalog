<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->table('catalog_category', function (Schema\Table $c) {
            $c->text('seo2');
            $c->string('seo_title')->setDefault('');
            $c->string('seo_title2')->setDefault('');
            $c->string('meta_title')->setDefault('');
            $c->text('meta_desc');
            $c->text('meta_keywords');
        });

        $s->table('catalog_product', function (Schema\Table $c) {
            $c->text('seo2');
            $c->string('seo_title')->setDefault('');
            $c->string('seo_title2')->setDefault('');
        });
    }

    public function down(Schema $s)
    {
        $s->table('catalog_category', function (Schema\Table $c) {
            $c->dropColumns(['seo2', 'seo_title', 'seo_title2', 'meta_title', 'meta_desc', 'meta_keywords']);
        });

        $s->table('catalog_product', function (Schema\Table $c) {
            $c->dropColumns(['seo2', 'seo_title', 'seo_title2']);
        });
    }
};