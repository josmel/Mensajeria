<?php

require_once('Conexion.php');
require_once('EasyPhoneService.php');

class Main {

    protected $_db;
    protected $_easyPhone;

    public function __construct() {
        $this->_db = new Conexion;
        $this->_easyPhone = new EasyPhoneService();
    }

    public function sinAgendar() {
        $sql = $this->sqlSinAgendar();
        $query = $this->_db->EjecutarSQL($sql);
        if ($query) {
            while ($fila = $this->_db->fetch_asoc($query)) {
               $stateSend = $this->_easyPhone->sendMessage($fila, '+51');
               $this->updateDespachado($fila['id'], $stateSend);
            }
        }
        $this->_db->free_result($query);
    }

    
    public function sqlSinAgendar() {
        $minute = date('i');
        $hour = date('H');
        $dateIni = date('Y-m-d ' . $hour . ':' . $minute . ':00');
        $dateFin = date('Y-m-d ' . $hour . ':' . $minute . ':59');
        return "SELECT message,phone,dateOffice,id, flagAgend 
                FROM sms WHERE
                 flagAgend=1  AND flagAct=1 ORDER BY id DESC
               ";
    }

    // dateOffice is not null  AND idtypesend=2 AND dateOffice BETWEEN '".$dateIni."' AND '".$dateFin."'
    /**
     * Actualiza el sms a despachado y cambia los estados 
     * 
     * @param INT $idSMS
     * @param INT $idStateSMS 1 no actualiza, 2 o 4 actualiza
     */
    public function updateDespachado($idSMS, $idStateSMS) {
        if ($idStateSMS != 1) {
            $sql = " UPDATE sms SET idstatesms='" . $idStateSMS . "', "
                    . "flagAgend=0 WHERE id ='" . $idSMS . "'";
            $this->_db->EjecutarSQL($sql);
        }
    }

 

}

$Main = new Main();
$test = $Main->sinAgendar();
//print_r($test);
