<?php

require_once('Conexion.php');
require_once(__DIR__ . '/../source/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php');
require_once(__DIR__ . '/../source/vendor/phpoffice/phpexcel/Classes/PHPExcel.php');
require_once(__DIR__ . '/../source/app/models/Interfaces/ProviderInterface.php');
require_once(__DIR__ . '/../source/app/models/Repositories/SmartWayRepository.php');
require_once(__DIR__ . '/../source/app/models/Services/EasyPhoneService.php');
require_once(__DIR__ . '/../source/app/models/Services/SaveSmsService.php');
require_once(__DIR__ . '/../source/app/models/Services/SaveSmsServiceExcel.php');
require_once(__DIR__ . '/../source/app/classes/validateNum.php');
require_once(__DIR__ . '/../source/vendor/PHPMailer_v5.1/PHPMailer_v5.1/class.phpmailer.php');

class Read {

    protected $_db;

    public function __construct() {
        $this->_db = new Conexion;
    }

    public function readFileExcel() {
        $this->consultarShippping();
    }

    public function cronExcel($id, $idUser, $typesend, $file_shipping) {
       
        $classType = explode('.', $file_shipping);
        $inputFileType = strtoupper($classType[1]);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setDelimiter(";");
        $objPHPExcel = $objReader->load(__DIR__ . '/../source/app/storage/files/' . $file_shipping);
        $objWorksheet = $objPHPExcel->getActiveSheet()->toArray();
        $sms = array();
        foreach ($objWorksheet as $key => $value) {
            if (isset($value[0])) {
                if (is_numeric($value[0]) && strlen($value[0]) == 9) {
                    if (isset($value[1])) {
                        if (is_string($value[1]) && strlen($value[1]) <= 160) {
                            $comment = 'ok';
                            $flagAgen = 1;
                        } else {
                            $comment = 'maximo de caracteres permitidos en el sms';
                            $flagAgen = 0;
                        }
                        $msj = $value[1];
                    } else {
                        $msj = null;
                        $comment = 'ok';
                        $flagAgen = 1;
                    }
                    $num = $value[0];
                } else {
                    $msj = isset($value[1]) ? $value[1] : null;
                    $num = $value[0];
                    $comment = 'numero invalido';
                    $flagAgen = 0;
                }
            } else {
                $msj = isset($value[1]) ? $value[1] : null;
                $num = null;
                $flagAgen = 0;
                $comment = 'numero vacio';
            }
            $fec = isset($value[2]) ? $value[2] : null;
            $sms[] = array($num, $msj, $fec, $comment, $flagAgen);
        }
        $dataSms = array(
            'idshipping' => $id,
            'idUser' => $idUser,
            'typesend' => $typesend,
            // 'idcompany' => $arguments['d'],
            'codCountry' => '+51',
        );
        $this->sendMessage($sms, $dataSms);
        return;
    }

    public function sendMessage(array $collection, array $params) {
        $reponseSend = 1;
        $response = false;
        foreach ($collection as $key => $obj) {
            $getOperatorPhone = new \validateNum();
            $operatorPhone = $getOperatorPhone->getOperadorNum($obj[0]);
            if ($operatorPhone['id'] != 4) {
                $reponseSend = 1;
            } else {
                $reponseSend = 2;
            }
            $dataSend = $this->dataSMS($obj, $params, $reponseSend);
            $devolver = $this->sqlSms($dataSend);
            echo $this->Agendar($devolver);
            PHP_EOL;
        }
    }

    public function consultarShippping() {

        $sql = $this->sqlShipping();
        $query = $this->_db->EjecutarSQL($sql);
        $data = array();
        if ($query) {
            while ($fila = $this->_db->fetch_asoc($query)) {
                $data[] = $fila;
                $this->cronExcel($fila['id'], $fila['idUser'], $fila['typesend'], $fila['file_shipping']);
                $this->updateShipping($fila['id']);
                $this->sqlShippingCantidad($fila['id']);
            }
        }

        $this->_db->free_result($query);
    }

    public function sqlShipping() {
        return "SELECT * FROM shippings where flag_agend=1 and flagAct=0 ";
    }

    public function sqlShippingCantidad($idShipping) {

        $sql = "SELECT idshipping,idUser,COUNT(IF(idstatesms = '1',1,NULL))  'Pendiente',
       COUNT(IF(idstatesms = '2',1,NULL)) 'Rechazado',
       COUNT(IF(idstatesms = '3',1,NULL)) 'Fallido',
       COUNT(IF(idstatesms = '4',1,NULL)) 'Despachado',
       COUNT(IF(idstatesms = '5',1,NULL)) 'Confirmado'
       FROM  sms  where idshipping='" . $idShipping . "'";
        $query = $this->_db->EjecutarSQL($sql);
        $data = array();
        if ($query) {
            while ($fila = $this->_db->fetch_asoc($query)) { $data[] = $fila;
                $this->enviarMail($fila['idshipping'], $fila['Pendiente'],
                        $fila['Rechazado'], $fila['Fallido'],
                        $fila['Despachado'], $fila['Confirmado']);
            }
        }

        $this->_db->free_result($query);
    }

    public function updateShipping($idShipping) {
        //if($idShipping!=1){
        $sql = " UPDATE shippings SET flag_agend=0,flagAct=1 WHERE id ='" . $idShipping . "'";
        $this->_db->EjecutarSQL($sql);
        // }
    }

    private function dataSMS($obj, $params, $reponseSend) {
        $getOperatorPhone = new \validateNum();
        $operatorPhone = $getOperatorPhone->getOperadorNum($obj[0]);
        return array(
            'idshipping' => $params['idshipping'],
            'idUser' => $params['idUser'],
            'phone' => $obj[0],
            'message' => $obj[1],
            'idstatesms' => $reponseSend, //state - confirmado -despachado
            'idoperator' => $operatorPhone['id'],
            'idtypesend' => (empty($obj[2])) ? $params['typesend'] : '2',
            'dateCreate' => date('Y-m-d H:i:s'),
            'dateShipping' => date('Y-m-d H:i:s'),
            'dateOffice' => (empty($obj[2])) ? null : $obj[2],
            'flagAct' => 1,
            'comment' => $obj[3],
            'flagAgend' => $obj[4], //(empty($obj[2]))?0:1,
        );
    }

    public function Agendar($sql) {
        $query = $this->_db->EjecutarSQL($sql);
        $this->_db->free_result($query);
        return $this->_db->last_id();
    }

    public function sqlSms($data) {
        return "INSERT INTO sms (idshipping,idUser,phone,message,idstatesms,idoperator,idtypesend,dateCreate,dateShipping,dateOffice,flagAct,flagAgend,comment) VALUES 
         ('" . $data['idshipping'] . "','" . $data['idUser'] . "','" . $data['phone'] . "','" . $data['message'] . "' ,'" . $data['idstatesms'] . "','" . $data['idoperator'] . "','" . $data['idtypesend'] . "','" . $data['dateCreate'] . "','" . $data['dateShipping'] . "', '" . $data['dateOffice'] . "','" . $data['flagAct'] . "','" . $data['flagAgend'] . "','" . $data['comment'] . "') ";
    }

    public function enviarMail($id, $Pendiente, $Rechazado, $Fallido, $Despachado,$Confirmado) {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = "mail.onlinestudioproductions.com";
        $mail->Username = "no_reply@onlinestudioproductions.com";
        $mail->Password = "ujf758Pw";
        $mail->Port = 25;
        $mail->From = "no_reply@smartmobileway.com";
        $mail->FromName = "Estado de Sms";
        $mail->AddAddress("fcardenas@smartmobileway.com");
        $mail->AddAddress("marcelo@onlinestudioproductions.com");
        $mail->IsHTML(true); //  fcardenas@smartmobileway.com // 
        $mail->Subject = "detalle de envio de sms";
        $body = " DETALLE DE ENVIO DE SMS<br><br>";
        $body .= "IdShipping: <strong>$id</strong><br>";
        $body .= "Pendiente: <strong>$Pendiente</strong><br>";
        $body .= "Rechazado :<strong>$Rechazado</strong><br>";
        $body .= "Fallido :<strong>$Fallido</strong><br>";
        $body .= "Despachado :<strong>$Despachado</strong><br>";
        $body .= "Confirmado :<strong>$Confirmado</strong><br>";
        $mail->Body = $body;
        $exito = $mail->Send();
        if ($exito) {
            echo "El correo fue enviado correctamente.";
        } else {
            echo "Hubo un inconveniente. Contacta a un administrador.";
        }
    }

}

$Main = new Read();
$test = $Main->readFileExcel();
//print_r($test);









