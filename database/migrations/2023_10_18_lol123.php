<?php

use \Simflex\Core\DB\Schema;

return new class implements \Simflex\Core\DB\Migration {
    public function up(Schema $s)
    {
        \Simflex\Core\DB::query('insert into struct_data (npp, table_id, field_id, name, label, help, placeholder, params) values (?, ?, ?, ?, ?, ?, ?, ?)', [
            34, 8, 1, 'other_phone', 'Телефон', '', '', 'a:1:{s:4:"main";a:14:{s:2:"pk";s:1:"0";s:3:"e2n";s:1:"0";s:6:"hidden";s:1:"0";s:5:"width";s:1:"0";s:12:"defaultValue";s:0:"";s:8:"required";s:1:"0";s:6:"filter";s:1:"0";s:8:"onchange";s:0:"";s:8:"readonly";s:1:"0";s:10:"style_cell";s:0:"";s:12:"screen_width";s:1:"0";s:9:"width_mob";s:1:"0";s:3:"pos";s:5:"right";s:9:"pos_group";s:33:"Другой получатель";}}'
        ]);
    }

    public function down(Schema $s)
    {

    }
};