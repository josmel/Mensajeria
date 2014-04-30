<?php

class OperatorTableSeeder extends Seeder {
 
    public function run()
    {
        $data = array(
            array('idcountry' => 1 , 'name' => 'Claro', 'dateCreate' => date('Y-m-d h:i:s'), 'lastUpdate' => date('Y-m-d h:i:s'), 'flagAct' => 1),
            array('idcountry' => 1 , 'name' => 'Movistar', 'dateCreate' => date('Y-m-d h:i:s'), 'lastUpdate' => date('Y-m-d h:i:s'), 'flagAct' => 1),
        );
 
        DB::table('operators')->insert($data);
    }
 
}