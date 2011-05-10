<?php
require_once 'Zend/Http/Client.php';
/**
 *
 * The Client/Executor for message requests
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
class Skulk_Client extends Zend_Http_Client
{
    /**
     *
     * a default application name declaration
     * @var unknown_type
     */
    const SKULK_NAME = 'Skulk/PHP';
    protected $endpoint = 'https://api.prowlapp.com/publicapi';
    /**
     *
     * Enter description here ...
     * @var Zend_Http_Client_Adapter_Interface
     */
    protected $httpClient;
    public function __construct ($config = null)
    {
        parent::__construct($this->endpoint, $config);
    }
    /**
     * <p>
     * This method sends a prowl message based on the message container parameters
     * There are (4) parameters required to successfully send
     * - apikey','application','event','description
     * - application
     * - event
     * - description
     *
     * @param Skulk_Client_Message $message
     * @throws Skulk_Client_Exception
     */
    public function add (Skulk_Client_Message $message)
    {
        $error = array();
        $data = $message->toArray();
        foreach (array('apikey', 'application', 'event', 'description') as $testItem) {
            if (! array_key_exists($testItem, $data)) {
                $error[] = $testItem;
            }
        }
        if (count($error)) {
            require_once 'Skulk/Client/Exception.php';
            throw new Skulk_Client_Exception(
            'required fields:[' . implode(':', $error) .
             '] not present for add call');
        } else {
            $this->setUri($this->endpoint . '/add');
            foreach ($data as $key => $value) {
                $this->setParameterPost($key, $value);
            }
            $response = $this->request(Zend_Http_Client::POST);
            require_once 'Skulk/Client/Response.php';
            return new Skulk_Client_Response($response->getRawBody());
        }
    }
    /**
     *
     * Enter description here ...
     * @param Skulk_Client_Message $message
     * @throws Skulk_Client_Exception
     */
    public function verify (Skulk_Client_Message $message)
    {
        if (! $message->getApikey()) {
            require_once 'Skulk/Client/Exception.php';
            throw new Skulk_Client_Exception('apikey key must be provided');
        }
        $this->setUri($this->endpoint . '/verify');
        $this->setParameterGet('apikey', $message->getApikey());
        if ($message->getProviderkey()) {
            $this->setParameterGet('providerkey', $message->getProviderkey());
        }
        $response = $this->request(Zend_Http_Client::GET);
        require_once 'Skulk/Client/Response.php';
        return new Skulk_Client_Response($response->getRawBody());
    }
    /**
     *
     * Enter description here ...
     * @param Skulk_Client_Message $message
     * @throws Skulk_Client_Exception
     */
    public function retrieveToken (Skulk_Client_Message $message)
    {
        if (! $message->getProviderkey()) {
            require_once 'Skulk/Client/Exception.php';
            throw new Skulk_Client_Exception('provider key must be provided');
        }
        $this->setUri($this->endpoint . '/retrieve/token');
        $this->setParameterGet('providerkey', $message->getProviderkey());
        $response = $this->request(Zend_Http_Client::GET);
        require_once 'Skulk/Client/Response.php';
        return new Skulk_Client_Response($response->getRawBody());
    }
    /**
     *
     * Enter description here ...
     * @param Skulk_Client_Message $message
     * @throws Skulk_Client_Exception
     */
    public function retrieveApikey (Skulk_Client_Message $message)
    {
        if (! $message->getProviderkey() || ! $message->getToken()) {
            require_once 'Skulk/Client/Exception.php';
            throw new Skulk_Client_Exception(
            'provider and token must be provided');
        }
        $this->setUri($this->endpoint . '/retrieve/apikey');
        $this->setParameterGet('providerkey', $message->getProviderkey());
        $this->setParameterGet('token', $message->getToken());
        $response = $this->request(Zend_Http_Client::GET);
        require_once 'Skulk/Client/Response.php';
        return new Skulk_Client_Response($response->getRawBody());
    }
}