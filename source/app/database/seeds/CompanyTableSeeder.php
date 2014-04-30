<?php

class CompanyTableSeeder extends Seeder {
 
    public function run()
    {
        $data = array(
            array('idcountry' => 1, 'name' => 'americatel', 'userUpdate' => 1, 'dateCreate' => date('Y-m-d h:i:s'), 'lastUpdate' => date('Y-m-d h:i:s'), 'flagAct' => 1),
        );
 
        DB::table('companys')->insert($data);
    }
 
}