<?php
require_once ('Zend/Tool/Project/Provider/Abstract.php');
require_once 'Zend/Tool/Framework/Provider/Pretendable.php';
/**
 * @author zircote
 *
 * zf enable config.provider Skulk_Tool_ProwlProvider
 * zf create config
 * zf ? prowl
 */
class Skulk_Tool_ProwlProvider extends Zend_Tool_Project_Provider_Abstract implements
Zend_Tool_Framework_Provider_Pretendable
{
    /**
     *
     * @var Skulk_Client_Message
     */
    protected $message;
    /**
     *
     * @var Skulk_Client
     */
    protected $api;
    /**
     *
     * @example zf addkey prowl iPhone sdf234g9i24t09j23r...
     * @param string $keyname
     * @param string $apikey
     */
    public function addkey ($keyname, $apikey)
    {
        if (! $this->_registry->getConfig()->skulk) {
            $this->_registry->getResponse()
                ->appendContent('initializing default config for Skulk...',
            array('color' => 'cyan'))
                ->appendContent('default priority [normal]...',
            array('color' => 'cyan'));
            $this->_registry->getConfig()->skulk = array(
            'defaults' => array('priority' => 'normal'),
            'keys' => array($keyname => $apikey));
            $this->_registry->getConfig()->save();
        } else {
            if ($this->_registry->getConfig()->skulk->keys) {
                $this->_registry->getConfig()->skulk->keys->$keyname = $apikey;
            } else {
                $this->_registry->getConfig()->skulk->keys = array();
                $this->_registry->getConfig()->skulk->keys->$keyname = $apikey;
            }
            $this->_registry->getConfig()->save();
        }
        $this->_registry->getResponse()->appendContent('api key saved!',
        array('color' => 'green'));
    }
    /**
     *
     * zf delkey prowl iPhone
     * @param string $keyname
     */
    public function delkey ($keyname)
    {
        $config = $this->_registry->getConfig();
        if ($config->skulk->keys->$keyname) {
            unset($config->skulk->keys->$keyname);
            $this->_registry->getConfig()->save();
            $this->_registry->getResponse()->appendContent(
            $keyname . ' apikey removed...', array('color' => 'red'));
        } else {
            $this->_registry->getResponse()->appendContent(
            $keyname . ' apikey does not exist...', array('color' => 'red'));
        }
    }
    /**
     *
     * zf send-message prowl 'test message' normal 'long description here' 'http://zircote.com'
     * @param string $message
     * @param string $priority
     * @param string $url
     * @param string $description
     */
    public function sendMessage ($message, $priority = 'normal', $description = null,
    $url = null)
    {
        $this->_init();
        $this->message->setApikey($this->apikeys->toArray())
            ->setEvent($message)
            ->setPriority($this->getPriority($priority))
            ->setDescription($description ? $description : $message);
        if (null !== $url) {
            $this->message->setUrl($url);
        }
        $response = $this->api->add($this->message);
        $result = $response->getResult();
        if (key_exists('success', $result)) {
            $this->_registry->getResponse()->appendContent(
            $response->getRemaining() . ' messages left until ' .
             $response->getResetDate(), array('color' => 'cyan'));
        } else {
            $this->_registry->getResponse()->appendContent(
            $result['error']['detail'], array('color' => 'red'));
        }
    }
    /**
     *
     * returns integer value for priorityfrom a simple string
     * @param string $priority
     * @return integer
     */
    protected function getPriority ($priority)
    {
        $priorities = array('verylow' => Skulk_Client_Message::PRIORITY_VERYLOW,
        'normal' => Skulk_Client_Message::PRIORITY_NORMAL,
        'moderate' => Skulk_Client_Message::PRIORITY_MODERATE,
        'high' => Skulk_Client_Message::PRIORITY_HIGH,
        'emergency' => Skulk_Client_Message::PRIORITY_EMERGENCY);
        return $priorities[strtolower($priority)];
    }
    protected function _init ()
    {
        $this->apikeys = $this->_registry->getConfig()->skulk->keys;
        require_once 'Skulk/Client.php';
        require_once 'Skulk/Client/Message.php';
        $this->message = new Skulk_Client_Message();
        $this->api = new Skulk_Client();
    }
}

