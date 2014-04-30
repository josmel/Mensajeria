<?php

class UserTableSeeder extends Seeder {
 
    public function run()
    {
        $data = array(
            array('idcompany' => 1, 'name' => 'Rolly', 'lastName' => 'Sanchéz', 'username' => 'rolly', 'password' => '$2y$10$En/x7N1eR.R3pFn7NZocnO2W3yF2NwBI9DA0NlIZyE8MNs3Hqt2uC', 'dateCreate' => date('Y-m-d h:i:s'), 'lastUpdate' => date('Y-m-d h:i:s'), 'flagAct' => 1),
        );
 
        DB::table('users')->insert($data);
    }
 
}