<?php
require_once ('Zend/Log/Writer/Abstract.php');
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
 * @subpackage Skulk_Log
 * @author     Robert Allen <zircote@zircote.com>
 * @copyright  2011 Robert Allen
 * @license    Copyright 2010 Robert Allen
 * @version    Release: @package_version@
 * @link       http://pear.zircote.com/
 *
 * <code>
 * $prowl = array(
 *     'apikey' => '072a7159e...e36ebe57',
 *     'priority' => Skulk_Client_Message::PRIORITY_EMERGENCY,
 *     'url' => 'http://www.zircote.com/admin/console',
 *     'event' => 'Error logging via Prowl with Zend_Log',
 *     'providerkey' => '072a7159e9e8f......e7765cd11c229e36ebe57'
 * );
 * $prowlWriter = Skulk_Log_Writer_Prowl::factory($prowl);
 * $prowlWriter->addFilter(new Zend_Log_Filter_Priority(Zend_Log::EMERG));
 * $zendLog = new Zend_Log($this->Skulk_Log_Writer_Prowl);
 * $zendLog->info('This wont be sent');
 * $zendLog->emerg('this will be sent');
 * </code>
 */
class Skulk_Log_Writer_Prowl extends Zend_Log_Writer_Abstract
{
    /**
     *
     * @var Skulk_Client_Message
     */
    protected $_skulk;
    /**
     *
     * @var Skulk_Client
     */
    protected $_client;
    /**
     *
     * This is the outbound message container, we send all at once on shutdown
     * @var array
     */
    protected $_messages = array();
    /**
     *
     * @param  Skulk_Client_Message $skulk
     * @return void
     */
    public function __construct(Skulk_Client_Message $skulk)
    {
        $this->_skulk = $skulk;
        include_once 'Zend/Log/Formatter/Simple.php';
        $this->_formatter = new Zend_Log_Formatter_Simple();
        $this->_client = new Skulk_Client();
    }
    /**
     * Create a new instance of Skulk_Log_Writer_Prowl
     *
     * @param  array|Zend_Config $config
     * @return Skulk_Log_Writer_Prowl
     */
    static public function factory($config)
    {
        $config = self::_parseConfig($config);
        $skulk = self::_constructSkulkFromConfig($config);
        $writer = new self($skulk);

        return $writer;
    }
    static public function _constructSkulkFromConfig($config)
    {
        $skulk = new Skulk_Client_Message();
        if(isset($config['apikey'])){
            $skulk->setApikey($config['apikey']);
        } else {
            require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception();
        }
        if(isset($config['priority'])){
            $skulk->setPriority($config['priority']);
        }
        if(isset($config['url'])){
            $skulk->setUrl($config['url']);
        }
        if(isset($config['event'])){
            $skulk->setEvent($config['event']);
        }
        if(isset($config['providerkey'])){
            $skulk->setProviderkey($config['providerkey']);
        }
        return $skulk;
    }
    /**
     * Write a message to the log.
     *
     * @param  array  $event  log data event
     * @return void
     */
    protected function _write($event)
    {
        array_push($this->_messages, $this->_formatter->format($event));

    }
    /**
     * (non-PHPdoc)
     * @see Zend_Log_Writer_Abstract::shutdown()
     */
    public function shutdown()
    {
        if(count($this->_messages)){
            $description = implode(null, $this->_messages);
            $this->_skulk->setDescription($description);
            $response = $this->_client->add($this->_skulk);
            $response->getResult();
        }
    }
	/**
     * @return Skulk_Client_Message
     */
    public function getSkulk ()
    {
        return $this->_skulk;
    }

	/**
     * @return Skulk_Client
     */
    public function getClient ()
    {
        return $this->_client;
    }

	/**
     * @param Skulk_Client_Message $_skulk
     * @return Skulk_Log_Writer_Prowl
     */
    public function setSkulk ($_skulk)
    {
        $this->_skulk = $_skulk;
        return $this;
    }

	/**
     * @param Skulk_Client $_client
     * @return Skulk_Log_Writer_Prowl
     */
    public function setClient ($_client)
    {
        $this->_client = $_client;
        return $this;
    }
	/**
     * @return the $_messages
     */
    public function getMessages ()
    {
        return $this->_messages;
    }



}
