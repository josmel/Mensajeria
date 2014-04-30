<?php

/**
 * 
 * @author  Rolly Sánchez
 * @version 1.0.0
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Smart Mobile Way
 * @api
 */

class Smw {
    
        const URL_WS = 'http://sms.smartmobileway.com';
    
        protected $_url_proxy = null;
        protected $_auth_proxy = null;
        protected $_token = null;
        protected $_file_session = null;
        protected $_user_id = null;
        protected $_campaign_id = null;

        /**
         * Setea una coneccion mediante proxy, si es que pasamos los parametros
         * 
         * @param string $urlProxy  Url proxy ejem: http://proxy.com/request
         * @param string $authProxy  Usuario y Password del proxy ejem: admin:123456
         */
        
        public function __construct($urlProxy = null, $authProxy = null) 
        {
            if(!empty($urlProxy)){
                $this->_url_proxy = $urlProxy;
                if(!empty($authProxy)){
                    $this->_auth_proxy = $authProxy;
                }
            }
        }
        
        /**
         * 
         * Login en el sistema Sms
         * 
         * @param string $user  Usuario
         * @param string $pass  Password
         * @return obj
         */
        
        public function auth($user, $pass)
        {

            $this->_file_session = ($this->windowsCheck())?md5($user).".tmp":'/tmp/'. md5($user).".tmp";

            //Lo primero, creamos una variable iniciando curl, pasándole la url
            $ch = curl_init(self::URL_WS.'/api/auth');

            //especificamos el POST
            curl_setopt ($ch, CURLOPT_POST, 1);

            //Proxy
            if(!empty($this->_url_proxy)) curl_setopt($ch, CURLOPT_PROXY, $this->_url_proxy);
            if(!empty($this->_auth_proxy)) curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->_auth_proxy);

            //le decimos qué paramáetros enviamos
            curl_setopt ($ch, CURLOPT_POSTFIELDS, "user=$user&pass=$pass");

            //Guardamos las cookies
            curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_file_session);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_file_session);

            //le decimos que queremos recoger una respuesta (si no esperas respuesta, ponlo a false)
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

            //Guardamos la session
            curl_setopt($ch, CURLOPT_COOKIESESSION, $this->_file_session);

            //recogemos la respuesta
            $response = curl_exec ($ch);

            //o el error, por si falla
            $error = curl_error($ch);

            //y finalmente cerramos curl
            curl_close ($ch);
            
            if($response){
                $json = json_decode($response);
                $this->_token = (isset($json->state) && $json->state)?$json->token:null;
                $this->_user_id = (isset($json->state) && $json->state)?$json->id:null;
                return $json;
            }elseif($error){
                return array('state' => 0, 'msj' => $error);
            }else{
                return array('state' => 0, 'msj' => 'error');
            }
//            return ($error)?array('state' => 0, 'msj' => $error):$json;
        }
        
        /**
         * Envio de mensajes de texto
         * 
         * @param string $phone  Telefono
         * @param string $message Mensaje
         * @param integer $campaign  campaña
         * @return obj
         */
        
        public function sendSms($phone, $message, $campaign = true)
        {

            //Lo primero, creamos una variable iniciando curl, pasándole la url
            $ch = curl_init(self::URL_WS.'/api/send/sms');

            //especificamos el POST
            curl_setopt ($ch, CURLOPT_POST, 1);

            //Proxy
            if(!empty($this->_url_proxy)) curl_setopt($ch, CURLOPT_PROXY, $this->_url_proxy);
            if(!empty($this->_auth_proxy)) curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->_auth_proxy);

            //le decimos qué paramáetros enviamos
            $params = "user=$this->_user_id&phone=$phone&message=$message&_token=$this->_token";
            if(!empty($this->_campaign_id) && $campaign){
                $params .= '&campaign='.$this->_campaign_id;
            }
            curl_setopt ($ch, CURLOPT_POSTFIELDS, $params);

            //Guardamos las cookies
            curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_file_session); 
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_file_session); 

            //le decimos que queremos recoger una respuesta (si no esperas respuesta, ponlo a false)
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

            //Guardamos la session
            curl_setopt($ch, CURLOPT_COOKIESESSION, $this->_file_session);

            //recogemos la respuesta
            $response = curl_exec ($ch);

            //o el error, por si falla
            $error = curl_error($ch);

            //y finalmente cerramos curl
            curl_close ($ch);

            return ($error)?array('state' => 0, 'msj' => $error):json_decode($response);
        }
        
        /**
         * Crear campaña
         * 
         * @param string $campaign Nombre de la campaña
         * @return object
         */
        
        public function campaign($campaign)
        {
            
            $ch = curl_init(self::URL_WS.'/api/campaign');
            curl_setopt ($ch, CURLOPT_POST, 1);
            if(!empty($this->_url_proxy)) curl_setopt($ch, CURLOPT_PROXY, $this->_url_proxy);
            if(!empty($this->_auth_proxy)) curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->_auth_proxy);
            $params = "user=$this->_user_id&campaign=$campaign";
            curl_setopt ($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_file_session); 
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_file_session); 
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_COOKIESESSION, $this->_file_session);
            $response = curl_exec ($ch);
            $error = curl_error($ch);
            curl_close ($ch);
            if($response){
                $json = json_decode($response);
                $this->_campaign_id = (isset($json->state) && $json->state)?$json->code:null;
                return $json;
            }elseif($error){
                return array('state' => 0, 'msj' => $error);
            }else{
                return array('state' => 0, 'msj' => 'error');
            }
        }
        
        /**
         * Logout
         * 
         * @param string $user Id del usuario
         * @return object 
         */
        
        public function logout()
        {
            
            $ch = curl_init(self::URL_WS.'/api/logout');
            curl_setopt ($ch, CURLOPT_POST, 1);
            if(!empty($this->_url_proxy)) curl_setopt($ch, CURLOPT_PROXY, $this->_url_proxy);
            if(!empty($this->_auth_proxy)) curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->_auth_proxy);
            $params = "user=$this->_user_id";
            curl_setopt ($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_file_session); 
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_file_session); 
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_COOKIESESSION, $this->_file_session);
            $response = curl_exec ($ch);
            $error = curl_error($ch);
            curl_close ($ch);
            if($response){
                $json = json_decode($response);
                return $json;
            }elseif($error){
                return array('state' => 0, 'msj' => $error);
            }else{
                return array('state' => 0, 'msj' => 'error');
            }
        }
        
        /**
         * Verificar si el s.o es windows
         * 
         * @return bool
         */
        
        function windowsCheck()
        {
            return in_array(strtolower(PHP_OS), array("win32", "windows", "winnt"));
        }

        /**
         * Verificar si el s.o es linux
         * 
         * @return type
         */
        
        function linuxCheck()
        {
            return in_array(strtolower(PHP_OS), array("linux"));
        }
}

?>
