<?php

//require_once 'Skulk/Client.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Skulk_Client test case.
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
 */
class Skulk_ClientTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Skulk_Client
	 */
	private $Zend_Http_Client_Adapter_Test;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		require_once 'Zend/Http/Client/Adapter/Test.php';
		$this->Zend_Http_Client_Adapter_Test = new Zend_Http_Client_Adapter_Test();
		
		$this->sharedFixture = array(
			'add' => "HTTP/1.1 200 OK"        . "\r\n" .
   					 "Content-type: text/xml" . "\r\n" .
                     "\r\n" .'<?xml version="1.0" encoding="UTF-8"?>
			<prowl>
				<success code="200" remaining="REMAINING" resetdate="UNIX_TIMESTAMP" />
			</prowl>',
			'error' => "HTTP/1.1 200 OK"        . "\r\n" .
   					   "Content-type: text/xml" . "\r\n" .
                       "\r\n" .'<?xml version="1.0" encoding="UTF-8"?>
			<prowl>
				<error code="ERRORCODE">ERRORMESSAGE</error>
			</prowl>',
			'retrieveToken' =>  "HTTP/1.1 200 OK"        . "\r\n" .
   					 			"Content-type: text/xml" . "\r\n" .
                        		"\r\n" .'<?xml version="1.0" encoding="UTF-8"?>
			<prowl>
				<success code="200" remaining="REMAINING" resetdate="UNIX_TIMESTAMP" />
				<retrieve token="TOKEN" url="URL" />
			</prowl>',
			'retrieveApikey' =>  "HTTP/1.1 200 OK"        . "\r\n" .
   					 			 "Content-type: text/xml" . "\r\n" .
                     			 "\r\n" .'<?xml version="1.0" encoding="UTF-8"?>
			<prowl>
				<success code="200" remaining="REMAINING" resetdate="UNIX_TIMESTAMP" />
				<retrieve apikey="APIKEY" />
			</prowl>',
			'invalid' => '   "HTTP/1.1 200 OK"        . "\r\n" .
   					 		 "Content-type: text/xml" . "\r\n" .
                      		 "\r\n" .<   23refsd2werf3rrhgrthjgsdfl',
			'nonXML' =>  "HTTP/1.1 200 OK"        . "\r\n" .
   						 "Content-type: text/xml" . "\r\n" .
                     	 "\r\n" .'<html><message>something</message></html>'
		);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		parent::tearDown ();
		$this->Zend_Http_Client_Adapter_Test = null;
	}
	
	/**
	 * Tests Skulk_Client->add()
	 */
	public function testAdd() {
		// TODO Auto-generated Skulk_ClientTest->testAdd()
		$this->Zend_Http_Client_Adapter_Test->setResponse($this->sharedFixture['add']);
		$options = array(
			'apikey' => array('APIKEY','APIKEY2'),
			'event' => 'EVENT',
			'description' => 'DESCRIPTION'
		);
		$message = new Skulk_Client_Message($options);
		$client = new Skulk_Client(array('adapter'   => 'Zend_Http_Client_Adapter_Test'));
		$result = $client->add($message);
		$this->assertEquals('https://api.prowlapp.com:443/publicapi/add', $client->getUri()->__toString());
		$pattern = 'apikey=APIKEY%2CAPIKEY2&application=Skulk%2FPHP&event=EVENT&description=DESCRIPTION';
		$this->assertFalse(!strstr($client->getLastRequest(), $pattern));
		$client = $message = null;
	}
	
	/**
	 * Tests Skulk_Client->verify()
	 */
	public function testVerify() {
		$this->Zend_Http_Client_Adapter_Test->setResponse($this->sharedFixture['add']);
		$options = array(
			'apikey' => array('APIKEY','APIKEY2'),
			'providerkey' => 'PROVIDERKEY'
		);
		$message = new Skulk_Client_Message($options);
		$client = new Skulk_Client(array('adapter'   => 'Zend_Http_Client_Adapter_Test'));
		$result = $client->verify($message);
		$this->assertEquals('https://api.prowlapp.com:443/publicapi/verify', $client->getUri()->__toString());
		$pattern = 'apikey=APIKEY%2CAPIKEY2&providerkey=PROVIDERKEY';
		$this->assertFalse(!strstr($client->getLastRequest(), $pattern));
	
	}
	
	/**
	 * Tests Skulk_Client->retrieveToken()
	 */
	public function testRetrieveToken() {
		$this->Zend_Http_Client_Adapter_Test->setResponse($this->sharedFixture['add']);
		$options = array(
			'providerkey' => 'PROVIDERKEY'
		);
		$message = new Skulk_Client_Message($options);
		$client = new Skulk_Client(array('adapter'   => 'Zend_Http_Client_Adapter_Test'));
		$result = $client->retrieveToken($message);
		$this->assertEquals('https://api.prowlapp.com:443/publicapi/retrieve/token', $client->getUri()->__toString());
		$pattern = 'providerkey=PROVIDERKEY';
		$this->assertFalse(!strstr($client->getLastRequest(), $pattern));
	}
	
	/**
	 * Tests Skulk_Client->retrieveApikey()
	 */
	public function testRetrieveApikey() {
		
		$this->Zend_Http_Client_Adapter_Test->setResponse($this->sharedFixture['add']);
		$options = array(
			'providerkey' => 'PROVIDERKEY',
			'token' => 'TOKEN'
		);
		$message = new Skulk_Client_Message($options);
		$client = new Skulk_Client(array('adapter'   => 'Zend_Http_Client_Adapter_Test'));
		$result = $client->retrieveApikey($message);
		$this->assertEquals('https://api.prowlapp.com:443/publicapi/retrieve/apikey', $client->getUri()->__toString());
		$pattern = 'providerkey=PROVIDERKEY&token=TOKEN';
		$this->assertFalse(!strstr($client->getLastRequest(), $pattern));
	}

}

