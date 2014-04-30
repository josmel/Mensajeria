<?php
class Group extends Eloquent {
    
//	protected $primaryKey = 'id';
    
        protected $fillable = array('iduser', 'name', 'flagAct'); 
    
        private function validate($inputs)
        {
            $rules=array(
                'iduser'=>'required|integer',
                'name'=>'required',
                'flagAct'=>'required|integer',
            );
            $message=array(
                'required'=>'Este campo es requerido',
                'integer' => 'Este campo debe ser un numero entero'
            );
            $validator = \Validator::make($inputs, $rules,$message);
            return $validator;
        }
        
        public function saveOnlyGroup($data)
        {
            $result = DB::transaction(function() use ($data)
            {
                $data['flagAct'] = 1;
                $data['iduser'] = isset(Auth::user()->id)?
                    Auth::user()->id : $data['iduser'];
                $validator=$this->validate($data);
                $result=array(
                    'state'=>0,
                    'msg'=>$validator
                );
                if($validator->passes()){
                    if(empty($data['id'])){
    //                    Group::create($data);
                        $group = new Group;
                    }else{
                        $group = $this->find($data['id']);
                    }
                    $group->fill($data);
                    $save = $group->save();
                    if($save){
                        $result['state']=1;
                        $result['msg']='Se creo el grupo correctamente';
                        $result['id']=$group->id;
                    }else{                                         
                        $result['state']=0;
                        $result['msg']= 'No se a guardado los datos';    
                    }
                }else{
                    $result['state']=0;
                    $result['msg']= 'No se a guardado los datos';  
                }
            });
            return $result;
        }
        
        public function saveGroup($data){
            $result = DB::transaction(function() use ($data)
            {
                $data['flagAct'] = 1;
                $data['iduser'] = isset(Auth::user()->id)?
                    Auth::user()->id : $data['iduser'];
                $validator=$this->validate($data);
                $result=array(
                    'state'=>0,
                    'msg'=>$validator
                );
                if($validator->passes()){
                    if(empty($data['id'])){
    //                    Group::create($data);
                        $group = new Group;
                    }else{
                        $group = $this->find($data['id']);
                    }
                    $group->fill($data);
                    $save = $group->save();

                    if($save){
                        
                        GroupCustomer::where('idgroup',$group->id)
                                ->update(array('flagAct' => 0));
                        if($data['groupassociate'] == 1){
                            $groupCustomer = new GroupCustomer;
                            $arrUser = isset($data['customer'])?$data['customer']:array();
                            $responseGC = $groupCustomer->insertUserAll($group->id, $arrUser);
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