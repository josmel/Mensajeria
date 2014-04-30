<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class SaveSmsService implements ProviderInterface{
    
    
    public function sendMessage(array $collection, array $params)
    {
        $reponseSend = 1;
        $response = false;
        foreach ($collection as $key => $obj) {
            $getOperatorPhone = new \validateNum();
            $operatorPhone=  $getOperatorPhone->getOperadorNum($obj[0]);
            if($operatorPhone['id'] != 4){
                $reponseSend = 1;
            }else
                $reponseSend = 2;
            $dataSend = $this->dataSMS($obj, $params, $reponseSend);
            $reponseSend = 1;
            $response = \Sms::insert($dataSend);
            $this->log($obj, $reponseSend);
        }
        return $response;
    }
    
    
    private function sendMQ($obj){
        var_dump($obj);
        echo "envio a la cola";
    }
    
    private function dataSMS($obj, $params, $reponseSend)
    {
        $getOperatorPhone = new \validateNum();
        $operatorPhone =  $getOperatorPhone->getOperadorNum($obj[0]);
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
            'flagAgend' => 1,//(empty($obj[2]))?0:1,
        );
    }

    private function validateCredit($params)
    {
        try {
            $countsms = 0;
            $user = User::find($params['idUser']);
            if($user->typepayment == 1){
                if($user->credit >= count($params['collection'])){
                    $countsms = count($params['collection']);
                }else
                    $countsms = $user->credit;
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
    
    function log($obj, $reponseSend){
        $view_log = new Logger('Logs Send Sms');
        $view_log->pushHandler(new StreamHandler(storage_path('logs/'.date('Y').'/'.date('m').'/log-send-sms-'.date('Y-m-d').'.txt'), Logger::INFO));

        $state = array(
            1 => 'pendiente',
            2 => 'fallido',
            4 => 'despachado',
        );
        
        if(!is_dir(storage_path('logs/'.date('Y').'/'.date('m').'/'))){
            mkdir(storage_path('logs/'.date('Y').'/'.date('m').'/'), 0777, true);
        }
        $view_log->addInfo('phone: '. $obj[0] . '; msj: ' . $obj[1] . '; state: ' . $state[$reponseSend] . ', date: ' . date('Y-m-d H:i:s'));
//        $view_log->addWarning('send sms to '. $obj[0] . ' msj ' . $obj[1] . ', state ' , $state[$reponseSend]);
//        $view_log->addError('send sms to '. $obj[0] . ' msj ' . $obj[1] . ', state ' , $state[$reponseSend]);
    }
    
}
