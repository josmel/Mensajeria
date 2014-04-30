<?php 

namespace App\Modules\Dashboard\Controllers;

use Auth, Country, User, Validator,Redirect,Input, Hash, View;

class UserController extends \BaseController {

	public function editPerfil()
	{
            $data = User::find(Auth::user()->id);
            $country = Country::where('flagAct', '=', 1)->lists('name', 'id');
            return View::make('dashboard::user.perfil',array("user" => $data, 'country' => $country));
	}
        
        public function updatePerfil($id)
	{
            $user= new User();
            $state= $user->updateUser($id,Input::all());
            if($state["state"]==1){
                return Redirect::route("editperfil")->with("msg",$state["msg"]);
            }else{
                return Redirect::route("editperfil")->withErrors($state["msg"])->withInput(Input::all());;
            }
	}
        
        public function editPassword()
        {
            $data = User::find(Auth::user()->id);
            return View::make('dashboard::user.password',array("user" => $data));
        }
        
        public function updatePassword()
	{
            $user= new User();
            $state= $user->newPassword(Input::all());
            if($state["state"]==1){
                return Redirect::route("editpassword")->with("msg",$state["msg"]);
            }else{
                return Redirect::route("editpassword")->withErrors($state["msg"])->withInput(Input::all());;
            }
	}
        
        public function pruebaSms(){
            $payMethod="EasyPhoneService";
            $sendSms=new \SmartWayRepository(new $payMethod);
            $state=$sendSms->send(
                    array(
                        array("993434610","+51992054388","Pasame la voz si te llego el mensaje Brayan. Prueba EasyPhone","2014-03-06 09:56"),
                        array("993434610","+51993684430","Pasame la voz si te llego el mensaje Brayan. Prueba EasyPhone",null)
                    )
                   );
            var_dump($state);
        }
        
}