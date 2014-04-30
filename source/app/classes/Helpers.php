<?php

class Helpers {
    public static function requestMCA($mca) {
        try {
            $getRequest = explode('\\', $mca);
            $module = $getRequest[2];
            $getRequest = explode('@', $getRequest[count($getRequest)-1]);
            $controller = str_replace('Controller', '', $getRequest[0]);
            $action = $getRequest[count($getRequest) - 1];
            $return = array(
                'module' => $module,
                'controller' => $controller,
                'action' => $action,
            );
            return $return;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

/*******************************************************************************
 * 
 *  1 = prepago
 *  2 = postpago
 * 
 ******************************************************************************/
    
    public static function credit()
    {
//        $prepayment = \Company::find(\Auth::user()->idcompany);
        $credit = new stdClass();
        if(Auth::user()->typepayment == 1){
            $credit->credit = Auth::user()->credit;
            $credit->msj = 'Créditos';
        }else{
            $sms = \Sms::where('idUser', \Auth::user()->id)->where('flagAct', 1)->count();
            $credit->credit = $sms;
            $credit->msj = 'Créditos que has usado';
        }
        
        return $credit;
    }
}