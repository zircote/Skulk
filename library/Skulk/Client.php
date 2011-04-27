<?php
require_once 'Zend/Http/Client.php';

class Skulk_Client extends Zend_Http_Client {
	const SKULK_NAME = 'Skulk/PHP';
	
	protected $endpoint = 'https://api.prowlapp.com/publicapi/';
	
	/**
	 * 
	 * Enter description here ...
	 * @var Zend_Http_Client_Adapter_Interface
	 */
	protected $httpClient;
	
	public function add(Skulk_Client_Message $message) {
		$data = $message->toArray();
		if(!array_key_exists(array('apikey','application','event','description'), $data)){
			throw new Skulk_Client_Exception('required fields not present for add call');
		} else{
			$this->setUri($this->endpoint . '/add');
			foreach ($data as $key => $value) {
				$this->setParameterPost($key, $value);
			}
			$response = $this->request(Zend_Http_Client::POST);
		}
	}
	
	public function verify($apikey, $providerkey = null){
		$this->setUri($this->endpoint . '/verify');
		$this->setParameterPost('apikey', $apikey);
		if($providerkey){
			$this->setParameterPost('providerkey', $providerkey);
		}
		$response = $this->request(Zend_Http_Client::GET);
	}
	
	public function retrieveToken($providerkey){
		$this->setUri($this->endpoint . '/retrieve/token');
		$this->setParameterPost('providerkey', $providerkey);
		$response = $this->request(Zend_Http_Client::GET);
	}
	
	public function retrieveApikey($providerkey, $token){
		$this->setUri($this->endpoint . '/retrieve/apikey');
		$this->setParameterPost('providerkey', $providerkey);
		$this->setParameterPost('token', $token);
		$response = $this->request(Zend_Http_Client::GET);
	}
}