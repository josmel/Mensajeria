<?php

class EasyPhoneService implements ProviderInterface{
    
    const HOST_EASYPHONE= "67.228.249.34";
    const PORT_EASYPHONE= 8888;
    const USER_EASYPHONE= "SMW";
    const PASS_EASYPHONE= '5MW"=!$';
    
    protected $_msjSendSMS = array('Pendiente', 'Enviado');

    private $_connection=null;

    private function getConnection(){
        $this->_connection= new \Smpp();
        return $this->_connection->open(self::HOST_EASYPHONE, self::PORT_EASYPHONE, self::USER_EASYPHONE, self::PASS_EASYPHONE);
    }
    
    /**
     * 1 = pendiente
     * 2 = rechazado 
     * 4 = DESPACHADO
     * 
     * @param array $collection
     * @param array $params
     * @return type
     */
    public function sendMessage(array $collection, array $params){             
        try {
            $stateConnect=$this->getConnection();
    //        $states=array(); 
            $dataSend = array(); $totalSmsSend = 0; $reponseSend = 1; //pendiente
            $totalSmsAgend = 0; $smscount = 0;
            if($stateConnect){

                $params['collection'] = $collection;
                $countsms = $this->validateCredit($params);

                foreach ($collection as $obj) {
                    $smscount++;
//                   
                    if(!empty($obj[2])){
                        if($smscount <= $countsms){
                            $this->sendMQ($obj);
                            $totalSmsAgend++;
                        }
                    }else{
                        $remitter = isset($obj[3])?$obj[3]:'123456789';
                        $codeCountry = $params['codCountry'];
    //                    if($this->validateCredit($params)){
                        if($smscount <= $countsms){
                            //$states[$obj[1]]=$this->_connection->send_long($remitter, $obj[0], $obj[1]);
                            
                            $getOperatorPhone = new \validateNum();
                            $operatorPhone=  $getOperatorPhone->getOperadorNum($obj[0]);
                            
                            if($operatorPhone['id'] != 4){
                                $reponseSend = $this->_connection->send_long
                                    ($remitter, $codeCountry.$obj[0], $obj[1], 0, 0, false);
                                $reponseSend = ((int)$reponseSend == 0) ?2:4;
                            }else{
                                $reponseSend = 2;
                            }
                            
                            
                            if($reponseSend == 4){
                                $totalSmsSend += 1;
                            }
                        }
                    }
                    $dataSend[] = $this->dataSMS($obj, $params, $reponseSend);
                    $reponseSend = 1;
                }
    //            var_dump($dataSend);exit;
                $response = \Sms::insert($dataSend);
                $update = \User::find($params['idUser']);
                $update->credit = ($update->credit + ($countsms - ($totalSmsSend + $totalSmsAgend)));
                $update->save();

                $updateCompany = \Company::find($params['idcompany']);
                $updateCompany->credit = ($updateCompany->credit - ($totalSmsSend + $totalSmsAgend));
                $updateCompany->save();

                $this->_connection->close();
    //            return $states;
                return $response;
            }
            return $stateConnect;
        } catch (Exception $exc) {
            return array('state'=> 0, 'msj' => $exc->getMessage());
        }
    }
    
    private function validDateMsg($date){
        if(!empty($date)){
            $maxDate=strtotime(date('Y-m-d H:i', strtotime('+15 minute')));
            $date = strtotime($date);        
            if($date>=$maxDate){
               return true;
            }
        }
        return false;       
    }
    
    private function sendMQ($obj){
        var_dump($obj);
        echo "envio a la cola";
    }
    
    private function dataSMS($obj, $params, $reponseSend)
    {
        $getOperatorPhone = new \validateNum();
        $operatorPhone=  $getOperatorPhone->getOperadorNum($obj[0]);
        return array(
            'idshipping' => $params['idshipping'],
            'idUser' => $params['idUser'],
            'phone' => $obj[0],
            'message' => $obj[1],
            'idstatesms' => $reponseSend,//state - confirmado -despachado
            'idoperator' => $operatorPhone['id'],
            'idtypesend' => (empty($obj[2]))?$params['typesend']:'2',
            'dateCreate' => date('Y-m-d H:i:s'),
            'dateShipping' => date('Y-m-d H:i:s'),
            'dateOffice' => (empty($obj[2]))?null:$obj[2],
            'flagAct' => 1,
            'flagAgend'=>(empty($obj[2]))?0:1,
        );
    }

    private function validateCredit($params)
    {
////        $company = \Company::find($params['idcompany']);
//        if(\Auth::user()->typepayment == 1){
//            if(\Auth::user()->credit > 0)
//                return true;
//            else
//                return false;
//        }else
//            return true;
        
        try {
            $countsms = 0;
            $user = User::find($params['idUser']);
            if($user->typepayment == 1){
                if($user->credit >= count($params['collection'])){
                    $countsms = count($params['collection']);
                }else
                    $countsms = $user->credit;

    //            $updUser = \User::find($user->id);
                $user->credit = ($user->credit - $countsms);
                $user->save();
            }else{
                $countsms = count($params['collection']);
            }
            return $countsms;
        } catch (Exception $exc) {
            return array('state'=> 0, 'msj' => $exc->getMessage());
        }

    }
    
}
