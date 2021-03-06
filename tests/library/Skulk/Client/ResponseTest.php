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
class Skulk_Client_ResponseTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Skulk_Client_Response
     */
    private $Skulk_Client_Response;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();

        // TODO Auto-generated Skulk_Client_ResponseTest::setUp()
        $this->sharedFixture = array(
            'add' => '<?xml version="1.0" encoding="UTF-8"?>
            <prowl>
                <success code="200" remaining="REMAINING" resetdate="UNIX_TIMESTAMP" />
            </prowl>',
            'error' => '<?xml version="1.0" encoding="UTF-8"?>
            <prowl>
                <error code="ERRORCODE">ERRORMESSAGE</error>
            </prowl>',
            'retrieveToken' => '<?xml version="1.0" encoding="UTF-8"?>
            <prowl>
                <success code="200" remaining="REMAINING" resetdate="UNIX_TIMESTAMP" />
                <retrieve token="TOKEN" url="URL" />
            </prowl>',
            'retrieveApikey' => '<?xml version="1.0" encoding="UTF-8"?>
            <prowl>
                <success code="200" remaining="REMAINING" resetdate="UNIX_TIMESTAMP" />
                <retrieve apikey="APIKEY" />
            </prowl>',
            'invalid' => '  <   23refsd2werf3rrhgrthjgsdfl',
            'nonXML' => '<html><message>something</message></html>',
            'resetdate' => '<?xml version="1.0" encoding="UTF-8"?>
            <prowl>
                <success code="200" remaining="REMAINING" resetdate="1304032027" />
            </prowl>',
        );

    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }

    public function testAdd(){
         $client = new Skulk_Client_Response($this->sharedFixture['add']);
         $array = $client->getResult();
         $this->assertArrayHasKey('success', $array);
         $this->assertArrayNotHasKey('error', $array);
         $this->assertEquals('200', $array['success']['code']);
         $this->assertEquals('REMAINING', $array['success']['remaining']);
         $this->assertEquals('UNIX_TIMESTAMP', $array['success']['resetdate']);
         $this->assertEquals('REMAINING',$client->getRemaining());
         $client = null;
    }

    public function testError(){
         $client = new Skulk_Client_Response($this->sharedFixture['error']);
         $array = $client->getResult();
         $this->assertArrayNotHasKey('success', $array);
         $this->assertArrayHasKey('error', $array);
         $this->assertEquals('ERRORCODE', $array['error']['code']);
         $this->assertEquals('ERRORMESSAGE', $array['error']['message']);
         $this->assertFalse($client->getRemaining());
         $client = null;
    }

    public function testRetrieveToken(){
         $client = new Skulk_Client_Response($this->sharedFixture['retrieveToken']);
         $array = $client->getResult();
         $this->assertArrayNotHasKey('error', $array);
         $this->assertArrayHasKey('success', $array);
         $this->assertArrayHasKey('retrieve', $array);
         $this->assertEquals('200', $array['success']['code']);
         $this->assertEquals('REMAINING', $array['success']['remaining']);
         $this->assertEquals('UNIX_TIMESTAMP', $array['success']['resetdate']);
         $this->assertEquals('TOKEN', $array['retrieve']['token']);
         $this->assertEquals('URL', $array['retrieve']['url']);
         $this->assertEquals('REMAINING',$client->getRemaining());
         $client = null;
    }

    public function testRetrieveApikey(){
         $client = new Skulk_Client_Response($this->sharedFixture['retrieveApikey']);
         $array = $client->getResult();
         $this->assertArrayNotHasKey('error', $array);
         $this->assertArrayHasKey('success', $array);
         $this->assertArrayHasKey('retrieve', $array);
         $this->assertEquals('200', $array['success']['code']);
         $this->assertEquals('REMAINING', $array['success']['remaining']);
         $this->assertEquals('UNIX_TIMESTAMP', $array['success']['resetdate']);
         $this->assertEquals('APIKEY', $array['retrieve']['apikey']);
         $this->assertEquals('REMAINING',$client->getRemaining());
         $client = null;
    }

    public function testInvalid(){
         $client = new Skulk_Client_Response($this->sharedFixture['invalid']);
         $array = $client->getResult();
         $this->assertFalse($array);
         $client = null;
    }

    public function testNonXML(){
         $client = new Skulk_Client_Response($this->sharedFixture['nonXML']);
         $array = $client->getResult();
         $this->assertFalse($array);
         $client = null;
    }

    public function testGetDateReset(){
         $client = new Skulk_Client_Response($this->sharedFixture['resetdate']);
         $array = $client->getResult();
         $this->assertTrue($client->getResetDate() instanceof Zend_Date);
         $this->assertEquals('Apr 28, 2011 6:07:07 PM',(string) $client->getResetDate());
         $client = null;
    }

}

