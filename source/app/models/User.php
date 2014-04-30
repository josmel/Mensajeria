<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

        protected $fillable = array('idcompany', 'idcountry', 'name', 'lastName', 'phone', 
            'username', 'password', 'email', 'birthday', 'address', 'flagAct', 'typepayment', 'credit'); 
        
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
        
        public function updateUser($id,$data){
            $validator=$this->validForm($data);
            $result=array(
                "state"=>0,
                "msg"=>$validator
            );
            if($validator->passes()){
                $result["state"]=1;
                $result["msg"]= "Sus datos se actualizaron satisfactoriamente";
                $user= $this->find($id);
//                $user->name= $data['name'];
//                $user->username= $data['username'];
//                $user->password= Hash::make($data['password']);
//                $user->email= $data['email'];
                $user->fill($data);
                $user->save();
            }
            return $result;
        }
        
        private function validForm($inputs)
        {
            $rules=array(
                "name"=>"required",
                "username"=>"required",
                "lastName"=>"required",
                'phone'=>'required|digits:9|numeric',
//                'birthday' => 'date',
                "email"=>"email",
            );
            $message=array(
                "required"=>"Este campo es requerido",
                "email"=>"Ingrese un email válido",
                'numeric' => 'Ingrese solo numeros',
                'unique' => 'El valor ingresado ya existe',
                'digits' => 'Ingrese solo 9 digitos'
            );
            $validator = Validator::make($inputs, $rules,$message);
            return $validator;
        }
        
        public function newPassword($data)
        {
            $result = DB::transaction(function() use ($data)
            {
                $data['flagAct'] = 1;
                $validator=$this->validatePassword($data);
                $result=array(
                    'state' => 0,
                    'msg' => $validator
                );
                if($validator->passes()){
                    $table = $this->find(\Auth::user()->id);
                    if (Hash::check($data['password'], $table->password))
                    {
                        $table->password= Hash::make($data['newpasswordconfirm']);
                        $save = $table->save();
                    }else
                        $save = false;
                    if($save){
                        $result['state']=1;
                        $result['msg']= 'Sus datos se guardaron satisfactoriamente';
                    }else{
                        $result['state']=1;
                        $result['msg']= 'No se a guardado los datos';    
                    }
                }
                return $result;
            });
            return $result;
        }
        
        private function validatePassword($inputs)
        {
            $rules=array(
                'password'=>'required',
                'newpassword'=>'required',
                'newpasswordconfirm'=>'required|same:newpassword',
                'flagAct'=>'required|integer',
            );
            $message=array(
                'required'=>'Este campo es requerido',
                'integer' => 'Este campo debe ser un numero entero',
                'same' => 'Ingrese la mismas contraseña nueva',
            );
            $validator = \Validator::make($inputs, $rules,$message);
            return $validator;
        }
}