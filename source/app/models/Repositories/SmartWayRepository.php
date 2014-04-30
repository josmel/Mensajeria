<?php

class SmartWayRepository{

	private $_provider;

	public function  __construct(ProviderInterface $provides){
		$this->_provider=$provides;
	}
	public function send($collection, $params){
            $msg=$this->_provider->sendMessage($collection, $params);
            return $msg;
	}
}