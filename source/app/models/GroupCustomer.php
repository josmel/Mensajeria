<?php
class GroupCustomer extends Eloquent {
    
//	protected $primaryKey = 'id';
        
//        protected $table = '';
    
        protected $fillable = array('idgroup', 'idcustomer', 'flagAct'); 
    
        private function validate($inputs)
        {
            $rules=array(
                'idgroup'=>'required|integer',
                'idcustomer'=>'required|integer',
                'flagAct'=>'required|integer',
            );
            $message=array(
                'required'=>'Este campo es requerido',
                'integer' => 'Este campo debe ser un numero entero',
            );
            $validator = \Validator::make($inputs, $rules,$message);
            return $validator;
        }
        
        public function saveData($data){
            $result = DB::transaction(function() use ($data)
            {
                $data['flagAct'] = 1;
                $validator=$this->validate($data);
                $result=array(
                    'state' => 0,
                    'msg' => $validator
                );
                if($validator->passes()){
                    if(empty($data['id'])){
    //                    Group::create($data);
                        $table = new GroupCustomer;
                    }else{
                        $table = $this->find($data['id']);
                    }
                    $table->fill($data);
                    $save = $table->save();
                    
                    if($save){
                        $result['state']=1;
                        $result['msg']= 'Sus datos se guardaron satisfactoriamente';
                        $result['id']= $table->id;
                    }else{
                        $result['state']=0;
                        $result['msg']= 'No se a guardado los datos';    
                    }
                }
                return $result;
            });
            return $result;
        }
        
        public function allData($data)
        {
            $table = GroupCustomer::leftjoin('customers','group_customers.idcustomer','=','customers.id')
                    ->leftjoin('groups','groups.id','=','group_customers.idgroup')
                    ->select('customers.id', 'customers.phone', 'groups.id')
                    ->where('customers.flagAct', '=', 1)
                    ->where('group_customers.flagAct', '=', 1)
                    ->where('groups.id', '=', $data['idgroup'])
                    ->get();
            return $table;
        }
        
        public function insertGroupAll($idCustomer, $data)
        {
            $insert = array();
            foreach ($data as $key => $value) {
                $insert[] = array(
                    'idgroup' => $value,
                    'idcustomer' => $idCustomer,
                    'flagAct' => 1,
                );
            }
            if(count($data) > 0)
                $table = $this::insert($insert);
            else
                $table = true;
            return $table;
        }
        
        public function insertUserAll($idGroup, $data)
        {
            $insert = array();
            foreach ($data as $key => $value) {
                $insert[] = array(
                    'idgroup' => $idGroup,
                    'idcustomer' => $value,
                    'flagAct' => 1,
                );
            }
            if(count($data) > 0)
                $table = $this::insert($insert);
            else
                $table = true;
            return $table;
        }
}