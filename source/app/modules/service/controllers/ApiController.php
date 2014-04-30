<?php namespace App\Modules\Service\Controllers;

use Auth, Crypt, Input, Hash, Log, Shipping, User,Group;

class ApiController extends \BaseController {
	public function sms()
	{
            try {
//                if(Auth::check()){
                    if(Input::has('phone') && Input::has('message') 
                        && Input::has('user') && Input::has('_token')){
                        
                        $verifyUser = User::whereId(Input::get('user'))->whereToken(Input::get('_token'))->first();
                        
                        if($verifyUser != false){
                            
                             if(Input::has('campaign')){
                                 $response['state'] = 1;
                                 $response['msj'] = 'Su envío se realizo correctamente';
                                 $response['id'] = $this->ascii_to_string_only_numeric(Input::get('campaign'));
                             }else{
                                $data = array(
                                     'name' => 'ShipingWebService' .  rand(0, 999), 
                                     'idcountry' => 1,
                                     'typeEntry' => 1,
                                     'idtypesend' => Input::get('typesend', 1),
                                     'dateAgend' => Input::get('dateAgend', null),
                                     'idUser' => Input::get('user'),
                                     'campaign' => null,
                                     'flagCampaign' => 0,
                                 );
                                $shipping = new Shipping();
                                $response = $shipping->saveDataService($data);
                             }
                            
//                             $shipping = new Shipping();
//                             $response = $shipping->saveDataService($data);
                             if($response["state"]==1){
                                 $sms = array(
                                     array(Input::get('phone'), Input::get('message'), Input::get('dateAgend', null)),
                                 );
                                 $user = User::find(Input::get('user'));
                                 $dataSms = array(
                                     'idshipping' => $response['id'],
                                     'idUser' => Input::get('user'),// usuario
                                     'typesend' => Input::get('typesend', 1),
                                     'idcompany' => $user->idcompany, //idcompany
                                     'codCountry' => '+51',
                                 );

                                 $userCompany = User::where('flagAct', 1)->where('idcompany', $user->idcompany)->lists('id', 'id');

                                 if(count($sms)>0 && $this->sendSms($sms, $dataSms) && isset($userCompany[Input::get('user')]))
                                     return $response;
                                 else
                                     return array('state' => 0, 'msj' => 'msj no enviado');                            
                             }else 
                                 return $response;
                        }else{
                            return array('state' => 0, 'msj' => 'user invalido'); 
                        }
                        
                        
                    }else
                        return array('state' => 0, 'msj' => 'wrong params');
//                }else
//                    return array('state' => 0, 'msj' => 'Not login');
            } catch (Exception $exc) {
                return array('state' => 0, 'msj' => $exc->getMessage());
            }
	}
        
        private function sendSms($data, $params){
            $payMethod="EasyPhoneService";
            $sendSms=new \SmartWayRepository(new $payMethod);
            $reponse=$sendSms->send($data, $params);
            return $reponse;
        }
        
        public function auth()
        {
		$credentials = array(
			'username' => Input::get('user'),
			'password' => Input::get('pass'),
		);

		if (Auth::attempt($credentials))
		{
                    $token = Crypt::encrypt(hash::make(uniqid()));;
                    $user = User::whereUsername($credentials['username'])->first();
                    $user->token = $token;
                    $user->save();
		    return array('msj' => 'ok', 'state' => 1, 'token' => $token, 'id' => Auth::user()->id);
		}

		return array('msj' => 'Not login', 'state' => 0);
        }
        
        public function campaign()
        {
            $data = array(
                    'name' => 'ShipingWebServiceCampaign' .  rand(0, 999), 
                    'idcountry' => 1,
                    'typeEntry' => 1,
                    'idtypesend' => Input::get('typesend', 1),
                    'dateAgend' => Input::get('dateAgend', null),
                    'idUser' => Input::get('user'),
                    'campaign' => Input::get('campaign'),
                    'flagCampaign' => 1,
            );
            $shipping = new Shipping();
            $response = $shipping->saveDataService($data);
            return $response;
        }
        
        public function logout()
        {
            $user = User::find(Input::get('user'));
            $user->token = uniqid() . time();
            $user->save();
            return array('msj' => 'ok', 'state' => 1);
        }
        
        function ascii_to_string_only_numeric($ascii)
        {
            $ascii = (string)$ascii;
            
            $string = NULL;

            for ($i = 0; $i < (strlen($ascii) / 2); $i++)
            {
                $n = $i * 2;
                $n2 = $n + 1;
                $string .= chr($ascii[$n].$ascii[$n2]);
            }

            return($string);
        }
}