<?php
require_once('Smpp.php');
class EasyPhoneService {
    
    const HOST_EASYPHONE= "67.228.249.34";
    const PORT_EASYPHONE= 8888;
    const USER_EASYPHONE= "SMW";
    const PASS_EASYPHONE= '5MW"=!$';
    
    protected $_msjSendSMS = array('Pendiente', 'Enviado');
    protected $_smpp;
    private $_connection=null;

    public function __construct(){
        $this->_smpp= new Smpp();
        $this->_connection = $this->_smpp->open(self::HOST_EASYPHONE, 
            self::PORT_EASYPHONE, self::USER_EASYPHONE, self::PASS_EASYPHONE);
    }
    
    /**
     * Envia mensaje al proveedor
     * @param array $collection
     * @param string $codCountry
     * @return INT numero que define el estado
     */    
    public function sendMessage(array $obj,$codeCountry)
    {        
        try {
            $this->stateConexion();       
            $remitter = isset($obj[3])?$obj[3]:'123456789';                                                                 
            $reponse= $this->_smpp->send_long($remitter, $codeCountry.$obj['phone'],$obj['message'], 0, 0, false);
            $reponseSend = ((int)$reponse == 0) ?2:4;                                    
            return $reponseSend;            
        } catch (Exception $exc) {
            return $reponseSend = 1 ;
        }
    }
    
    public function closeConexionSmpp()
    {
         $this->_smpp->close();
    }
    
    public function stateConexion()
    {
        if($this->_connection){
            return true ;
        }else{
            throw new Exception('fallo conexion');
        }
    }
    
       
}
