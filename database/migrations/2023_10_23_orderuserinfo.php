<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        $s->table('catalog_order', function (Schema\Table $c) {
            $c->string('user_last_name');
            $c->string('user_name');
            $c->string('user_patronym');
            $c->string('user_phone');
        });

        \Simflex\Core\DB::query('insert into struct_data (npp, table_id, field_id, name, label, help, placeholder, params) 
values 
    (12, 42, 1, ?, ?, ?, ?, ?), 
    (12, 42, 1, ?, ?, ?, ?, ?), 
    (12, 42, 1, ?, ?, ?, ?, ?), 
    (12, 42, 1, ?, ?, ?, ?, ?)', [
            'user_last_name', 'Фамилия', '', '', 'a:1:{s:4:"main";a:14:{s:2:"pk";s:1:"0";s:3:"e2n";s:1:"0";s:6:"hidden";s:1:"0";s:5:"width";s:1:"0";s:12:"defaultValue";s:0:"";s:8:"required";s:1:"0";s:6:"filter";s:1:"0";s:8:"onchange";s:0:"";s:8:"readonly";s:1:"0";s:10:"style_cell";s:0:"";s:12:"screen_width";s:1:"0";s:9:"width_mob";s:1:"0";s:3:"pos";s:5:"right";s:9:"pos_group";s:20:"Покупатель";}}',
            'user_name', 'Имя', '', '', 'a:1:{s:4:"main";a:14:{s:2:"pk";s:1:"0";s:3:"e2n";s:1:"0";s:6:"hidden";s:1:"0";s:5:"width";s:1:"0";s:12:"defaultValue";s:0:"";s:8:"required";s:1:"0";s:6:"filter";s:1:"0";s:8:"onchange";s:0:"";s:8:"readonly";s:1:"0";s:10:"style_cell";s:0:"";s:12:"screen_width";s:1:"0";s:9:"width_mob";s:1:"0";s:3:"pos";s:5:"right";s:9:"pos_group";s:20:"Покупатель";}}',
            'user_patronym', 'Отчество', '', '', 'a:1:{s:4:"main";a:14:{s:2:"pk";s:1:"0";s:3:"e2n";s:1:"0";s:6:"hidden";s:1:"0";s:5:"width";s:1:"0";s:12:"defaultValue";s:0:"";s:8:"required";s:1:"0";s:6:"filter";s:1:"0";s:8:"onchange";s:0:"";s:8:"readonly";s:1:"0";s:10:"style_cell";s:0:"";s:12:"screen_width";s:1:"0";s:9:"width_mob";s:1:"0";s:3:"pos";s:5:"right";s:9:"pos_group";s:20:"Покупатель";}}',
            'user_phone', 'Телефон', '', '', 'a:1:{s:4:"main";a:14:{s:2:"pk";s:1:"0";s:3:"e2n";s:1:"0";s:6:"hidden";s:1:"0";s:5:"width";s:1:"0";s:12:"defaultValue";s:0:"";s:8:"required";s:1:"0";s:6:"filter";s:1:"0";s:8:"onchange";s:0:"";s:8:"readonly";s:1:"0";s:10:"style_cell";s:0:"";s:12:"screen_width";s:1:"0";s:9:"width_mob";s:1:"0";s:3:"pos";s:5:"right";s:9:"pos_group";s:20:"Покупатель";}}',
        ]);

        \Simflex\Core\DB::query('update struct_data set npp = 13, params = ? where name = ? and table_id = 42', [
            'a:1:{s:4:"main";a:14:{s:2:"pk";s:1:"0";s:3:"e2n";s:1:"1";s:6:"hidden";s:1:"0";s:5:"width";s:1:"1";s:12:"defaultValue";s:0:"";s:8:"required";s:1:"0";s:6:"filter";s:1:"1";s:8:"onchange";s:0:"";s:8:"readonly";s:1:"1";s:10:"style_cell";s:0:"";s:12:"screen_width";s:3:"991";s:9:"width_mob";s:3:"190";s:3:"pos";s:5:"right";s:9:"pos_group";s:20:"Покупатель";}}',
            'email'
        ]);
    }

    public function down(Schema $s)
    {
        $s->table('catalog_order', function (Schema\Table $c) {
            $c->dropColumns(['user_last_name', 'user_name', 'user_patronym', 'user_phone']);
        });
    }
};