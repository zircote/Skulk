<?php

/**
 * 
 * The Request Envelope
 * @author zircote
 * @package Skulk_Client
 *
 */
class Skulk_Client_Message {
	/**
	 * 
	 * Very Low
	 * @var integer
	 */
	const PRIORITY_VERYLOW 		= '-2';
	/**
	 * 
	 * Moderate
	 * @var integer
	 */
	const PRIORITY_MODERATE 	= '-1';
	/**
	 * 
	 * Normal
	 * @var integer
	 */
	const PRIORITY_NORMAL 		=  '0';
	/**
	 * 
	 * High
	 * @var integer
	 */
	const PRIORITY_HIGH 		=  '1';
	/**
	 * 
	 * Emergency
	 * Emergency priority messages may bypass quiet hours according to the user's settings.
	 * @var integer
	 */
	const PRIORITY_EMERGENCY 	=  '2';
	/**
	 * 
	 * API keys separated by commas. Each API key is a 40-byte hexadecimal string.
	 * @var array
	 */
	protected $apikey = array();
	/**
	 * 
	 * Your provider API key. Only necessary if you have been whitelisted.
	 * @var string [40]
	 */
	protected $providerkey;
	/**
	 * 
	 * Default value of 0 if not provided. An integer value ranging [-2, 2] representing:
	 * @var integer
	 */
	protected $priority;
	/**
	 * 
	 * The name of your application or the application generating the event.
	 * @var string [256]
	 */
	protected $application;
	/**
	 * 
	 * A description of the event, generally terse.
	 * @var string [10000]
	 */
	protected $description;
	/**
	 * 
	 * The name of the event or subject of the notification.
	 * @var string [1024]
	 */
	protected $event;
	/**
	 * 
	 * Requires Prowl 1.2 The URL which should be attached to the notification.
	 * This will trigger a redirect when launched, and is viewable in the notification list.
	 * @var string [512]
	 */
	protected $url;
	
	public function __construct($message = null){
        if ($message instanceof Zend_Config) {
            $message = $message->toArray();
        }
        if(is_array($message)){
	        foreach ($message as $key => $value) {
	        	$method_name = "set".ucfirst($key);
	        	if(method_exists($this, $method_name)){
	        		$this->$method_name($value);
	        	}
	        }
        }
	}
	
	/**
	 * @return the $apikey
	 */
	public function getApikey() {
		return $this->apikey;
	}

	/**
	 * @param array $apikey
	 * @return Skulk_Client_Message
	 */
	public function setApikey($apikey) {
		if(is_array($apikey)){
			$this->apikey = $apikey;
		} else {
			$this->apikey = array($apikey);
		}
		return $this;
	}

	/**
	 * @return the $providerkey
	 */
	public function getProviderkey() {
		return $this->providerkey;
	}

	/**
	 * @param string $providerkey
	 * @return Skulk_Client_Message
	 */
	public function setProviderkey($providerkey) {
		$this->providerkey = $providerkey;
		return $this;
	}

	/**
	 * @return the $priority
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * @param integer $priority
	 * @return Skulk_Client_Message
	 */
	public function setPriority($priority) {
		$priorities = array(
			self::PRIORITY_VERYLOW,
			self::PRIORITY_NORMAL,
			self::PRIORITY_MODERATE,
			self::PRIORITY_HIGH,
			self::PRIORITY_EMERGENCY
		);
		if(in_array($priority, $priorities)){
			$this->priority = $priority;
		} else {
			throw new Skulk_Client_Exception('invalid priority level submitted', 501);
		}
		return $this;
	}

	/**
	 * @return the $application
	 */
	public function getApplication() {
		if(!$this->application){
			$this->setApplication();
		}
		return $this->application;
	}

	/**
	 * @param string $application
	 * @return Skulk_Client_Message
	 */
	public function setApplication() {
		$this->application = Skulk_Client::SKULK_NAME;
		return $this;
	}

	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return Skulk_Client_Message
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * @return the $event
	 */
	public function getEvent() {
		return $this->event;
	}

	/**
	 * @param string $event
	 * @return Skulk_Client_Message
	 */
	public function setEvent($event) {
		$this->event = $event;
		return $this;
	}

	/**
	 * @return the $url
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param string $url
	 * @return Skulk_Client_Message
	 */
	public function setUrl($url) {
		require_once 'Zend/Uri/Http.php';
		if(Zend_Uri_Http::check($url)){
			$this->url = $url;
		} else {
			throw new Skulk_Client_Exception("[{$url}] is invalid", 501);
		}
		return $this;
	}

	public function toArray(){
		$return = array();
		foreach (array('url', 'priority', 'providerkey', 'apikey', 'application', 'event', 'description') as $prop) {
			$return[$prop] = $this->$prop;
		};
		return $return;
	}
}