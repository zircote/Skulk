<?php

//require_once 'Skulk/Client/Message.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Skulk_Client_Message test case.
 */
class Skulk_Client_MessageTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		parent::tearDown ();
	}
	
	public function testConstruct(){
		$options = array(
			'url' => 'http://chicago.com',
			'event' => 'EVENT',
			'priority' => Skulk_Client_Message::PRIORITY_HIGH
		);
		$message = new Skulk_Client_Message($options);
		$this->assertEquals(Skulk_Client_Message::PRIORITY_HIGH, $message->getPriority());
		$this->assertEquals('EVENT', $message->getEvent());
		$this->assertEquals('http://chicago.com', $message->getUrl());
	}
	
	public function testConstructZendConfig(){
		$options = array(
			'url' => 'http://chicago.com',
			'event' => 'EVENT',
			'priority' => Skulk_Client_Message::PRIORITY_HIGH
		);
		require_once 'Zend/Config.php';
		$options = new Zend_Config($options);
		$message = new Skulk_Client_Message($options);
		$this->assertEquals(Skulk_Client_Message::PRIORITY_HIGH, $message->getPriority());
		$this->assertEquals('EVENT', $message->getEvent());
		$this->assertEquals('http://chicago.com', $message->getUrl());
	}
	
	/**
	 * Tests Skulk_Client_Message->setApikey()
	 */
	public function testSetApikey() {
		$message = new Skulk_Client_Message();
		$this->assertTrue($message
			->setApikey('APIKEY') instanceof Skulk_Client_Message);
		$api = $message->getApiKey();
		$this->assertEquals('APIKEY', $api[0]);
		$message = null;
		$message = new Skulk_Client_Message();
		$this->assertTrue($message
			->setApikey(array('APIKEY')) instanceof Skulk_Client_Message);
		$api = $message->getApiKey();
		$this->assertEquals('APIKEY', $api[0]);
		$message = null;
	}
	
	/**
	 * Tests Skulk_Client_Message->setProviderkey()
	 */
	public function testSetProviderkey() {
		$message = new Skulk_Client_Message();
		$this->assertTrue($message
			->setProviderkey('PROVIDER_KEY') instanceof Skulk_Client_Message);
		$this->assertEquals('PROVIDER_KEY', $message->getProviderkey());
		$message = null;
	}
	
	/**
	 * Tests Skulk_Client_Message->setPriority()
	 */
	public function testSetPriority() {
		$message = new Skulk_Client_Message();
		$this->assertTrue($message
			->setPriority(Skulk_Client_Message::PRIORITY_EMERGENCY) instanceof Skulk_Client_Message);
		$this->assertEquals(Skulk_Client_Message::PRIORITY_EMERGENCY, $message->getPriority());
	}
	
	/**
	 * Tests Skulk_Client_Message->setApplication()
	 */
	public function testSetApplication() {
		$message = new Skulk_Client_Message();
		$this->assertEquals(Skulk_Client::SKULK_NAME, $message->getApplication());
		$message = null;
	}
	
	/**
	 * Tests Skulk_Client_Message->setDescription()
	 */
	public function testSetDescription() {
		$message = new Skulk_Client_Message();
		$this->assertTrue($message
			->setDescription('DESCRIPTION') instanceof Skulk_Client_Message);
		$this->assertEquals('DESCRIPTION', $message->getDescription());
		$message = null;
	}
	
	/**
	 * Tests Skulk_Client_Message->setEvent()
	 */
	public function testSetEvent() {
		$message = new Skulk_Client_Message();
		$this->assertTrue($message->setEvent('EVENT') instanceof Skulk_Client_Message);
		$this->assertEquals('EVENT', $message->getEvent());
		$message = null;
	}
	
	/**
	 * Tests Skulk_Client_Message->setUrl()
	 */
	public function testSetUrl() {
		$message = new Skulk_Client_Message();
		$this->assertTrue($message->setUrl('http://chicago.com') instanceof Skulk_Client_Message);
		$this->assertEquals('http://chicago.com', $message->getUrl());
		$message = null;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @expectedException Skulk_Client_Exception
	 */
	public function testInvalidUrl(){
		$message = new Skulk_Client_Message();
		$message->setUrl('fizzlesticks');
		$message = null;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @expectedException Skulk_Client_Exception
	 */
	public function testInvalidPriority(){
		$message = new Skulk_Client_Message();
		$message->setPriority('120');
		$message = null;
	}
}

