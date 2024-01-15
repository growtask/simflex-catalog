<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->table('user', function (Schema\Table $c) {
            $c->integer('discount')->setDefault(0);
        });

        \Simflex\Core\DB::query('insert into struct_data (npp, table_id, field_id, name, label, help, placeholder, params) values (1, 8, 2, ?, ?, ?, ?, ?)', [
            'discount', 'Персональная скидка', '', '', serialize([
                'main' => [
                    'pk' => 0,
                    'e2n' => 0,
                    'hidden' => 0,
                    'width' => 0,
                    'defaultValue' => '0',
                    'required' => 0,
                    'filter' => 0,
                    'onchange' => '',
                    'readonly' => '',
                    'style_cell' => '',
                    'screen_width' => '0',
                    'width_mob' => '0',
                    'pos' => 'right',
                    'pos_group' => 'Дополнительно'
                ]
            ])
        ]);
    }

    public function down(Schema $s)
    {
        $s->table('user', function (Schema\Table $c) {
            $c->dropColumns('discount');
        });
    }
};