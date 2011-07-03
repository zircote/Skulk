<?php

/**
 * @see Zend_Mail_Transport_Abstract
 */
require_once 'Zend/Mail/Transport/Abstract.php';
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
 *     'priority' => Skulk_Client_Message::PRIORITY_NORMAL,
 *     'url' => 'http://www.zircote.com/admin/console',
 *     'event' => 'Error logging via Prowl with Zend_Log',
 *     'providerkey' => '072a7159e9e8f......e7765cd11c229e36ebe57'
 * );
 * $transport = new Skulk_Mail_Transport_Prowl($prowl)
 * $mail = new Zend_Mail();
 * $mail->setBodyText('this is a skulk test')
 *     ->send($transport);
 * </code>
 */
class Skulk_Mail_Transport_Prowl extends Zend_Mail_Transport_Abstract
{
    /**
     * Target directory for saving sent email messages
     *
     * @var string
     */
    protected $_path;
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
     * Constructor
     *
     * @param  array|Zend_Config $options OPTIONAL (Default: null)
     * @return void
     */
    public function __construct($options = null)
    {
        if($options instanceof Skulk_Client_Message){
            $this->setSkulk($options);
        } elseif ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } elseif (!is_array($options)) {
            $options = array();
        }
        $this->setOptions($options);
    }

    /**
     * Sets options
     *
     * @param  array $options
     * @return void
     * @throws Zend_Mail_Transport_Exception
     */
    public function setOptions(array $options)
    {
        $skulk = new Skulk_Client_Message();
        if(isset($options['apikey'])){
            $skulk->setApikey($options['apikey']);
        } else {
            require_once 'Zend/Mail/Transport/Exception.php';
            throw new Zend_Mail_Transport_Exception(
                'a destination apikey must be provided'
            );
        }
        if(isset($options['priority'])){
            $skulk->setPriority($options['priority']);
        }
        if(isset($options['url'])){
            $skulk->setUrl($options['url']);
        }
        if(isset($options['event'])){
            $skulk->setEvent($options['event']);
        }
        if(isset($options['providerkey'])){
            $skulk->setProviderkey($options['providerkey']);
        }
        $this->setSkulk($skulk);
        $this->setClient(new Skulk_Client);
    }

    /**
     * deliver e-mail message to prowlapp
     *
     * @return void
     * @throws Zend_Mail_Transport_Exception on not writable target directory
     */
    protected function _sendMail()
    {
        $email = $this->header . $this->EOL . $this->body;
        $this->getClient()->add($this->getSkulk()->setDescription($email));
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
}
