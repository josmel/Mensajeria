<?php namespace App\Modules\Dashboard\Controllers;

use Auth, Customer, Input;

class AjaxController extends \BaseController {

	public function uniquePhoneCustomer()
	{
            if (Input::has('phone')){
                $customer = Customer::leftjoin('users','users.id','=','customers.iduser')
                        ->where('customers.phone','=',trim(Input::get('phone')))
                        ->where('users.idcompany', '=', Auth::user()->idcompany)
                        ->get(array('customers.phone'))->count();
                if($customer > 0){
                    echo 'false';
                }else{
                    echo 'true';
                }
            }else{
                echo 'false';
            }
	}
        
}
