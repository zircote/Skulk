<?php

require_once 'Skulk/Client.php';
require_once 'Skulk/Client/Message.php';
$msgOptions = array (
	'apikey' => 'APIKEY',
	'event' => 'This is a test message',
	'priority' => Skulk_Client_Message::PRIORITY_HIGH,
	'description' => 'just a random test message'
);
$message = new Skulk_Client_Message($msgOptions);
$config = array(
    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
    'curloptions' => array(
		CURLOPT_FOLLOWLOCATION => true, 
		CURLOPT_SSL_VERIFYPEER => false),
);
$client = new Skulk_Client($config);
$response = $client->add($message);
print_r($response->getResult());