<?php namespace App\Modules\Dashboard\Controllers;

use App, Auth, Customer, DB, Group, GroupCustomer, Input, Operator, Redirect, Session, URL, View;

class SuplyController extends \BaseController {

	public function showUser()
	{
                return View::make('dashboard::supply.user');
	}
        
	public function showUserOperation()
	{
                Session::put('route', 'suplyuseroperation');
                $operator = Operator::where('flagAct', '=', 1)->lists('name', 'id');
                $group = Group::where('flagAct', '=', 1)->where('groups.iduser', '=', Auth::user()->id)->lists('name', 'id');
                return View::make('dashboard::supply.useroperation', array('operator' => $operator, 'group' => $group));
	}
        
	public function editUserOperation($id)
	{
                Session::put('route', 'suplyuserupdateoperation');
                Session::put('routeParams', array('id' => $id));
                $operator = Operator::where('flagAct', '=', 1)->lists('name', 'id');
                $group = Group::where('flagAct', '=', 1)->where('groups.iduser', '=', Auth::user()->id)->lists('name', 'id');
                $customer = Customer::find($id);
                $groupSelect = GroupCustomer::where('idcustomer', $id)->where('flagAct', 1)->lists('idgroup');
//                var_dump($groupSelect);Exit;
                return View::make('dashboard::supply.useroperation', array('operator' => $operator, 'group' => $group, 
                    'customer' => $customer, 'groupSelect' => $groupSelect));
	}
        
        public function saveSupplyUser()
        {
                $customer = new Customer();
                $response = $customer->saveData(Input::all());
                if($response["state"]==1)
                    return Redirect::route('suplyuser')->with("msg",$response["msg"]);
                else 
                    return Redirect::route(Session::get('route', 'suplyuseroperation'), Session::get('routeParams', array()))->withErrors($response["msg"])->withInput(Input::all());
        }
        
        public function listSupplyUser()
        {
                $iDisplayStart = Input::get('iDisplayStart', 0);
                $iDisplayLength = Input::get('iDisplayLength', 10);
                $sEcho = Input::get('sEcho', 1);
                $sSearch = Input::get('sSearch', null);
                $params = array(
                    'name' => Input::get('sSearch_2', null),
                    'lastname' => Input::get('sSearch_3', null),
                    'phone' => Input::get('sSearch_0', null),
                );
//                var_dump($params);exit;
                $data = Customer::leftjoin('operators','operators.id','=','customers.idoperator')
                        ->leftjoin('users','users.id','=','customers.iduser')
                        ->select('customers.id', 'customers.phone', 'customers.created_at', 'customers.lastName as clastname', 
                                'customers.name as cname', 'users.name as uname', 'operators.name as oname')
                        ->where('customers.flagAct', '=', 1)
                        ->where('customers.iduser', '=', Auth::user()->id)
                        ->where(function($query) use ($params){
                           $query->where('customers.name', 'like', '%'.$params['name'].'%')
                           ->where('customers.lastName', 'like', '%'.$params['lastname'].'%')
                           ->where('customers.phone', 'like', '%'.$params['phone'].'%');
                        });
                $old = $data->orderBy('customers.id');
                $pag = $data->paginate($iDisplayLength);
                $data = $old->skip($iDisplayStart)->take($iDisplayLength)->get();
                $rptas = array();
                
                foreach ($data as $value){
                    $rptas[]=array(
                        0 => $value->phone,
                        1 => $value->oname,
                        2 => $value->cname,
                        3 => $value->clastname,
                        4 => $value->uname,
                        5 => $value->created_at->toDateTimeString(),
                        6 => '
                             <a data-id="'.$value->id.'" class="tblaction ico-edit" href="'.URL::route('suplyuserupdateoperation', array('id' => $value->id)) .'"></a>
                             <a data-id="'.$value->id.'" data-table="Customer" class="tblaction ico-delete" href="'.URL::route('supplydelete') .'"></a>',
                    ); 
                }
//                $totalCustomer = Customer::where('flagAct', 1)->count();
                $rpta = array(
                    'aaData' => $rptas,
                    'sEcho' => $sEcho,
                    'iTotalRecords' => $pag->getTotal(),
                    'iTotalDisplayRecords' => $pag->getTotal(),
                );

                return $rpta;
        }
        
        
	public function showGroup()
	{
                return View::make('dashboard::supply.group');
	}
        
	public function showGroupOperation()
	{
                $customer = Customer::where('flagAct', '=', 1)->where('customers.iduser', '=', Auth::user()->id)->lists('name', 'id');
                return View::make('dashboard::supply.groupoperation', array('customer' => $customer));
	}
        
	public function editGroupOperation($id)
	{
                Session::put('routeGroup', 'suplygroupupdateoperation');
                Session::put('routeGroupParams', array('id' => $id));
                $customer = Customer::where('flagAct', '=', 1)->where('customers.iduser', '=', Auth::user()->id)->lists('name', 'id');
                $group = Group::find($id);
                $customerSelect = GroupCustomer::where('idgroup', $id)->where('flagAct', 1)->lists('idcustomer');
//                echo '<pre>';
//                var_dump($customerSelect);Exit;
//                echo '</pre>';
                return View::make('dashboard::supply.groupoperation', array('group' => $group, 'customer' => $customer, 
                    'customerSelect' => $customerSelect));
	}

        public function saveSupplyGroup()
        {
                $group = new Group();
                $response = $group->saveGroup(Input::all());
                if($response["state"]==1)
                    return Redirect::route('suplygroup')->with("msg", $response["msg"]);
                else 
                    return Redirect::route(Session::get('routeGroup', 'suplygroupoperation'), Session::get('routeGroupParams', array()))->withErrors($response["msg"])->withInput(Input::all());
        }
        
        public function listSupplyGroup()
        {
                $iDisplayStart = Input::get('iDisplayStart', 0);
                $iDisplayLength = Input::get('iDisplayLength', 10);
                $sEcho = Input::get('sEcho', 1);
                $sSearch = Input::get('sSearch', null);

                $data = Group::leftjoin('users','users.id','=','groups.iduser')
                        ->select(DB::raw('(SELECT count(gc.idcustomer) FROM group_customers gc WHERE gc.idgroup = groups.id AND gc.flagAct = 1) as user_count, 
                            users.name as uname, groups.id, groups.name, groups.created_at'))
                        ->where('groups.name', 'like', '%'.$sSearch.'%')
                        ->where('groups.iduser', '=', Auth::user()->id)
                        ->where('groups.flagAct', '=', 1);
                $old = $data->orderBy('groups.id');
                $pag = $data->paginate($iDisplayLength);
                $data = $old->skip($iDisplayStart)->take($iDisplayLength)->get();
                $rptas = array();
                foreach ($data as $value){
                    $rptas[]=array(
                        0 => $value->id,
                        1 => $value->name,
                        2 => $value->user_count,
                        3 => $value->uname,
                        4 => $value->created_at->toDateTimeString(),
                        5 => '
                             <a data-id="'.$value->id.'" class="tblaction ico-edit" href="'.URL::route('suplygroupupdateoperation', array('id' => $value->id)) .'"></a>
                             <a data-id="'.$value->id.'" data-table="Group" class="tblaction ico-delete" href="'.URL::route('supplydelete') .'"></a>',
                    ); 
                }
//                $totalGroup = Group::where('flagAct', 1)->count();
                $rpta = array(
                    'aaData' => $rptas,
                    'sEcho' => $sEcho,
                    'iTotalRecords' => $pag->getTotal(),
                    'iTotalDisplayRecords' => $pag->getTotal(),
                );

                return $rpta;
        }
        
        public function deleteSupply()
        {
                $tableName = array('Group', 'Customer');
                $rpta = array(
                    'state' => 0,
                    'msj' => 'error',
                );
                if(in_array(Input::get('table'), $tableName)){
                    $tbl = Input::get('table');
                    $table = $tbl::find(Input::get('id'));
                    $table->flagAct = 0;
                    $save = $table->save();

                    if($save){
                        $rpta = array(
                            'state' => 1,
                            'msj' => 'ok',
                        );
                    }
                }
                return $rpta;
        }
}
