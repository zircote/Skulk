<?php

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
 *
 * @category   CategoryName
 * @package    Skulk
 * @author     Robert Allen <zircote@zircote.com>
 * @copyright  2011 Robert Allen
 * @license    Copyright 2010 Robert Allen
 * @version    Release: @package_version@
 * @link       http://pear.zircote.com/
 *
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
        $this->assertEquals('APIKEY', $api);
        $message = null;
        $message = new Skulk_Client_Message();
        $this->assertTrue($message
            ->setApikey(array('APIKEY','APIKEY2')) instanceof Skulk_Client_Message);
        $api = $message->getApiKey();
        $this->assertEquals('APIKEY,APIKEY2', $api);
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

