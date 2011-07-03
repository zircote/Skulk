<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Zend/Mail.php';
/**
 * Skulk_Mail_Transport_Prowl test case.
 */
class Skulk_Mail_Transport_ProwlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Skulk_Mail_Transport_Prowl
     */
    private $Skulk_Mail_Transport_Prowl;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        // TODO Auto-generated Skulk_Mail_Transport_ProwlTest::setUp()
         $prowl = array(
             'apikey' => '072a7159e9e8f......e7765cd11c229e36ebe57',
             'priority' => Skulk_Client_Message::PRIORITY_NORMAL,
             'url' => 'http://www.zircote.com/admin/mail',
             'event' => 'Error logging via ProwlTest with Zend_Mail',
//             'providerkey' => '072a7159e9e8f......e7765cd11c229e36ebe57'
         );
        $this->Skulk_Mail_Transport_Prowl = new Skulk_Mail_Transport_Prowl($prowl);
        print_r($this->Skulk_Mail_Transport_Prowl);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated Skulk_Mail_Transport_ProwlTest::tearDown()
        $this->Skulk_Mail_Transport_Prowl = null;
        parent::tearDown();
    }
    /**
     * Tests Skulk_Mail_Transport_Prowl->setOptions()
     * @group Skulk_Mail_Transport_Prowl
     */
    public function testSetOptions ()
    {
        $this->markTestSkipped();
        $mail = new Zend_Mail();
        $mail->addTo('zircote@gmail.comm')
            ->setBodyText('this is a skulk test')
            ->send($this->Skulk_Mail_Transport_Prowl);
    }
    /**
     * Tests Skulk_Mail_Transport_Prowl->getSkulk()
     */
    public function testGetSkulk ()
    {
        // TODO Auto-generated Skulk_Mail_Transport_ProwlTest->testGetSkulk()
        $this->markTestIncomplete("getSkulk test not implemented");
        $this->Skulk_Mail_Transport_Prowl->getSkulk(/* parameters */);
    }
    /**
     * Tests Skulk_Mail_Transport_Prowl->getClient()
     */
    public function testGetClient ()
    {
        // TODO Auto-generated Skulk_Mail_Transport_ProwlTest->testGetClient()
        $this->markTestIncomplete("getClient test not implemented");
        $this->Skulk_Mail_Transport_Prowl->getClient(/* parameters */);
    }
    /**
     * Tests Skulk_Mail_Transport_Prowl->setSkulk()
     */
    public function testSetSkulk ()
    {
        // TODO Auto-generated Skulk_Mail_Transport_ProwlTest->testSetSkulk()
        $this->markTestIncomplete("setSkulk test not implemented");
        $this->Skulk_Mail_Transport_Prowl->setSkulk(/* parameters */);
    }
    /**
     * Tests Skulk_Mail_Transport_Prowl->setClient()
     */
    public function testSetClient ()
    {
        // TODO Auto-generated Skulk_Mail_Transport_ProwlTest->testSetClient()
        $this->markTestIncomplete("setClient test not implemented");
        $this->Skulk_Mail_Transport_Prowl->setClient(/* parameters */);
    }
}

