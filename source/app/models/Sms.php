<?php
class Sms extends Eloquent {
    
//	protected $primaryKey = 'id';
        
        protected $table = 'sms';
    
        protected $fillable = array('idshipping', 'idUser', 'phone', 'message', 'idoperator',
            'state', 'idstatesms', 'idtypesend', 'dateCreate', 'dateShipping', 'dateOffice', 'flagAct'); 
    
        private function validate($inputs)
        {
            $rules=array(
                'idshipping'=>'required',
                'idUser'=>'required|integer',
                'phone'=>'required',
                'message'=>'required',
                'idoperator'=>'required',
                'idstatesms'=>'required',
                'idtypesend'=>'required',
                'dateCreate'=>'required',
                'dateShipping'=>'required',
                'dateOffice'=>'required',
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
                $data['idUser'] = Auth::user()->id;
                $validator=$this->validate($data);
                $result=array(
                    'state' => 0,
                    'msg' => $validator
                );
                if($validator->passes()){
                    if(empty($data['id'])){
    //                    Group::create($data);
                        $table = new Shipping;
                    }else{
                        $table = $this->find($data['id']);
                    }
                    $table->fill($data);
                    $save = $table->save();
                    
                    if($save){
                        $result['state']=1;
                        $result['msg']= 'Su mensaje se envio correctamente';
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
}