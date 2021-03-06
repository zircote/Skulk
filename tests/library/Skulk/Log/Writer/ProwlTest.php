<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Zend/Log.php';
/**
 * Skulk_Log_Writer_Prowl test case.
 */
class Skulk_Log_Writer_ProwlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Skulk_Log_Writer_Prowl
     */
    private $Skulk_Log_Writer_Prowl;
    /**
     * @var Zend_Log
     */
    public $Zend_Log;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        $this->sharedFixture = array(
            'apikey' => '072a7159e...e36ebe57',
            'priority' => Skulk_Client_Message::PRIORITY_EMERGENCY,
            'url' => 'http://www.zircote.com/admin/console',
            'event' => 'Error logging via Prowl with Zend_Log',
            'providerkey' => '072a7159e9e8f......e7765cd11c229e36ebe57'
        );
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        // TODO Auto-generated Skulk_Log_Writer_ProwlTest::tearDown()
        $this->Skulk_Log_Writer_Prowl = null;
        $this->Zend_Log = null;
        $this->sharedFixture = null;
        parent::tearDown();
    }
    /**
     *
     * Verify the factory generates the Writer correctly
     * @group Skulk_Log_Writer_Prowl
     */
    public function testFactory()
    {
        $writer = Skulk_Log_Writer_Prowl::factory($this->sharedFixture);
        $this->assertSame(
            $this->sharedFixture['priority'], $writer->getSkulk()->getPriority()
        );
        $this->assertSame(
            $this->sharedFixture['url'], $writer->getSkulk()->getUrl()
        );
        $this->assertSame(
            $this->sharedFixture['event'], $writer->getSkulk()->getEvent()
        );
        $this->assertSame(
            $this->sharedFixture['providerkey'], $writer->getSkulk()->getProviderkey()
        );
        $this->assertSame(
            $this->sharedFixture['apikey'], $writer->getSkulk()->getApikey()
        );
    }
    /**
     * Tests Skulk_Log_Writer_Prowl::factory()
     * @group Skulk_Log_Writer_Prowl
     */
    public function testLog ()
    {
        $this->markTestSkipped();
        $this->Skulk_Log_Writer_Prowl = Skulk_Log_Writer_Prowl::factory($this->Skulk_Log_Writer_Prowl);
        $filter = new Zend_Log_Filter_Priority(Zend_Log::EMERG);
        $this->Skulk_Log_Writer_Prowl->addFilter($filter);
        $this->Zend_Log = new Zend_Log($this->Skulk_Log_Writer_Prowl);
        $this->Zend_Log->info('this is a info test');;
        $this->Zend_Log->crit('this is a crit test');;
        $this->Zend_Log->emerg('this is a emerg test 1 ');
        $this->Zend_Log->emerg('this is a emerg test 2 ');
        $this->Zend_Log->emerg('this is a emerg test 3 ');
    }
}

