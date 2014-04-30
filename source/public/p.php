<?php

require('../app/classes/librarysmw.php');

$smw = new Smw();

$authResponse = $smw->auth('rolly', '123456');

$response = array('state' => 0, 'msj' => 'fail');

//$smw->campaign('campaña prueba');
if($authResponse->state){    
    $array = array('991890754', '961917131'/*, '989712993', '997531089'*/);
//    foreach ($array as $key => $value) {
//        $response = $smw->sendSms($value, 'jesuleado prueba');
//    }
    
    $response = $smw->sendSms('961917131', 'jesuleado prueba', false);
}

$smw->logout();
var_dump($response);

?>
