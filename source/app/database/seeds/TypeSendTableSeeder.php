<?php

class TypeSendTableSeeder extends Seeder {
 
    public function run()
    {
        $data = array(
            array('name' => 'Invidual', 'lastUpdate' => date('Y-m-d h:i:s'), 'flagAct' => 1),
            array('name' => 'Grupal', 'lastUpdate' => date('Y-m-d h:i:s'), 'flagAct' => 1),
            array('name' => 'Archivo', 'lastUpdate' => date('Y-m-d h:i:s'), 'flagAct' => 1),
        );
 
        DB::table('typesends')->insert($data);
    }
 
}