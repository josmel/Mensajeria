<?php
class Shipping extends Eloquent {
    
//	protected $primaryKey = 'id';
    
        protected $fillable = array('name', 'idcountry', 'typeEntry', 'idUser','file_shipping', 'dateAgend', 'flagAct', 'campaign', 'flagCampaign','flag_agend','typesend'); 
    
        private function validate($inputs)
        {
            $rules=array(
                'name'=>'required',
                'idUser'=>'required|integer',
                'idcountry'=>'required|integer',
//                'idtypesend'=>'required|integer',
                'flagAct'=>'required|integer',
            );
            $message=array(
                'required'=>'Este campo es requerido',
                'integer' => 'Este campo debe ser un numero entero'
            );
            $validator = \Validator::make($inputs, $rules,$message);
            return $validator;
        }
        
        public function saveData($data){
            try {

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
                            $result['msg']= 'Su envío se realizo correctamente';
                            $result['id']= $table->id;
                        }else{
                            $result['state']=0;
                            $result['msg']= 'No se a guardado los datos';    
                        }
                    }
                    return $result;
                });
                return $result;
            } catch (Exception $exc) {
                return array('state'=> 0, 'msg' => $exc->getMessage());
            }
        }
        
        public function saveSms(array $data)
        {
                $send = array();
                switch ($data['typesend']) {
                    case 1:
                        $send[] = array(
                            'idshipping',
                        );
                        break;
                    case 2:
                        break;
                    default:
                        break;
                }
                $sms = new \Sms();
                $sms->fill($dataSms);
        }
        
        
        
        public function editData($data){
            try {

                $result = DB::transaction(function() use ($data)
                {
                   // $data['flagAct'] = 1;
                    $data['idUser'] = Auth::user()->id;
                    $validator=$this->validate($data);
                    $result=array(
                        'state' => 0,
                        'msg' => $validator
                    );
                    //if($validator->passes()){
                        if(!empty($data['id'])){
        //                  
                            $table = $this->find($data['id']);
                        }
                        $table->fill($data);
                        $save = $table->save();

                        if($save){
                            $result['state']=1;
                            $result['msg']= 'Su envío se realizo correctamente';
                            $result['id']= $table->id;
                        }else{
                            $result['state']=0;
                            $result['msg']= 'No se a guardado los datos';    
                        }
                    //}
                    return $result;
                });
                return $result;
            } catch (Exception $exc) {
                return array('state'=> 0, 'msg' => $exc->getMessage());
            }
        }
        
        
        public function saveDataService($data){
            try {
                $result = DB::transaction(function() use ($data)
                {
                    $data['flagAct'] = 1;
    //                $data['idUser'] = 1; //usuario
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
                            $result['msg']= 'Su envío se realizo correctamente';
                            $result['id']= $table->id;
                            $result['code']= $this->string_to_ascii($table->id);
                        }else{
                            $result['state']=0;
                            $result['msg']= 'No se a guardado los datos';    
                        }
                    }
                    return $result;
                });
                return $result;
            } catch (Exception $exc) {
                return array('state'=> 0, 'msj' => $exc->getMessage());
            }
        }
        
        function string_to_ascii($string)
        {
            $string = (string)$string;
            
            $ascii = NULL;

            for ($i = 0; $i < strlen($string); $i++)
            {
            $ascii .= ord($string[$i]);
            }

            return($ascii);
        }
}