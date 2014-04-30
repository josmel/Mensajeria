<?php

class CountryTableSeeder extends Seeder {
 
    public function run()
    {
        $data = array(
            array('name' => 'Perú', 'dateCreate' => date('Y-m-d h:i:s'), 'lastUpdate' => date('Y-m-d h:i:s'), 'flagAct' => 1),
        );
 
        DB::table('countrys')->insert($data);
    }
 
}