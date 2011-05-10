<?php

/**
 * 
 * The Request Envelope
 * @author zircote
 * @package Skulk_Client
 * @license Copyright 2010 Robert Allen
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */
class Skulk_Client_Message {
    /**
     * 
     * Very Low
     * @var integer
     */
    const PRIORITY_VERYLOW         = '-2';
    /**
     * 
     * Moderate
     * @var integer
     */
    const PRIORITY_MODERATE     = '-1';
    /**
     * 
     * Normal
     * @var integer
     */
    const PRIORITY_NORMAL         =  '0';
    /**
     * 
     * High
     * @var integer
     */
    const PRIORITY_HIGH         =  '1';
    /**
     * 
     * Emergency
     * Emergency priority messages may bypass quiet hours according to the user's settings.
     * @var integer
     */
    const PRIORITY_EMERGENCY     =  '2';
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
    
    /**
     * 
     * The token returned from retrieve/token. Required.
     * @var string [40]
     */
    protected $token;
    
    /**
     * 
     * expects message details as parameter or null
     * @param Zend_Config|array|null $message
     */
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
     * @return string $apikey
     */
    public function getApikey() {
        return trim(implode(',', $this->apikey),',');
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
     * @return string $providerkey
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
     * @return integer $priority
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
            require_once 'Skulk/Client/Exception.php';
            throw new Skulk_Client_Exception('invalid priority level submitted', 501);
        }
        return $this;
    }

    /**
     * @return string $application
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
    public function setApplication($application = null) {
        if(null === $application){
            $application = Skulk_Client::SKULK_NAME;
        }
        $this->application = $application;
        return $this;
    }

    /**
     * @return string $description
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
     * @return string $event
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
     * @return string $url
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
            require_once 'Skulk/Client/Exception.php';
            throw new Skulk_Client_Exception("[{$url}] is invalid", 501);
        }
        return $this;
    }
    /**
     * 
     * returns the message contents as an array
     * @return array
     */
    public function toArray(){
        $return = array();
        foreach (array('url', 'priority', 'providerkey', 'apikey', 'application', 'event', 'description') as $prop) {
            $m = 'get'.ucfirst($prop);
            $return[$prop] = $this->$m();
        };
        return $return;
    }
    /**
     * @return string
     */
    public function getToken() {
        return $this->token;
    }
    /**
     * @param string $token
     * @return Skulk_Client_Message
     */
    public function setToken($token) {
        $this->token = $token;
        return $this;
    }

    
}