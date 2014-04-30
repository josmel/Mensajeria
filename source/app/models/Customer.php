<?php
class Customer extends Eloquent {

        protected $fillable = array('idoperator', 'iduser', 'name', 'lastName', 'birthday', 'codPhone', 'phone', 'flagAct'); 
    
        private function validate($inputs)
        {
            $rules=array(
//                'idoperator'=>'required|integer',
                'iduser'=>'required|integer',
                'name'=>'required',
                'lastName'=>'required',
                'phone'=>'required|digits:9|numeric',
                'birthday' => 'date',
                'flagAct'=>'required|integer',
            );
            $message=array(
                'required'=>'Este campo es requerido',
                'int' => 'Este campo debe ser un numero entero',
                'date' => 'Ingrese una fecha correcta',
                'numeric' => 'Ingrese solo numeros',
                'unique' => 'El valor ingresado ya existe',
                'digits' => 'Ingrese solo 9 digitos'
            );
            $validator = \Validator::make($inputs, $rules,$message);
            return $validator;
        }
        
        public function saveData($data){
            $result = DB::transaction(function() use ($data)
            {
                $data['flagAct'] = 1;
                $data['iduser'] = Auth::user()->id;
                $validator=$this->validate($data);
                $result=array(
                    'state' => 0,
                    'msg' => $validator
                );
                if($validator->passes()){
                    if(empty($data['id'])){
    //                    Group::create($data);
                        $table = new Customer;
                    }else{
                        $table = $this->find($data['id']);
                    }
                    $table->fill($data);
                    $save = $table->save();

                    if($save){
                        GroupCustomer::where('idcustomer',$table->id)
                                ->update(array('flagAct' => 0));
                        if($data['groupassociate'] == 1){
                            $groupCustomer = new GroupCustomer;
                            $arrGroup = isset($data['group'])?$data['group']:array();
                            $responseGC = $groupCustomer->insertGroupAll($table->id, $arrGroup);
                        }else{
                            $responseGC = true;
                        }
                        
                        if($responseGC){
                            $result['state']=1;
                            $result['msg']= 'Sus datos se guardaron satisfactoriamente'; 
                        }else{
                            $result['state']=0;
                            $result['msg']= 'No se a guardado los datos'; 
                        }
                    }else{
                        $result['state']=0;
                        $result['msg']= 'No se a guardado los datos';    
                    }
                }
                return $result;
            });
            return $result;
        }
        
}