# Using Skulk

## About

`
skulk  (skulk)
intr.v. skulked, skulk·ing, skulks
1. To lie in hiding, as out of cowardice or bad conscience; lurk.
2. To move about stealthily.
3. To evade work or obligation; shirk.
`
 A library for interacting with the prowl api.
 http://www.prowlapp.com/api.php
 
## Installation

 Install Zend Framework And Skulk:
 	pear channel-discover pear.zfcampus.org
	pear install zfcampus/zf
	pear channel-discover pear.zircote.com
	pear install zircote/Skulk-Alpha
 
 
## Methods

### add

 `Skulk_Client::add` allows you to send a message to one or more clients.
 
Setting parameters by methods.

	<?php
	require_once 'Skulk/Client.php';
	require_once 'Skulk/Client/Message.php';
	$message = new Skulk_Client_Message();
	$message->setApikey('APIKEY')
		->setEvent('This is a test message')
		->setPriority(Skulk_Client_Message::PRIORITY_HIGH)
		->setDescription('just a random test message');
	$config = array(
	    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
	    'curloptions' => array(
			CURLOPT_FOLLOWLOCATION => true, 
			CURLOPT_SSL_VERIFYPEER => false),
	);
	$client = new Skulk_Client($config);
	$response = $client->add($message);
	print_r($response->getResult());
	Array
	(
	    [success] => Array
	        (
	            [code] => 200
	            [remaining] => 994
	            [resetdate] => 1303947992
	        )
	
	)

define the message as an array as well as multiple apikey/destinations:

	<?php
	require_once 'Skulk/Client.php';
	require_once 'Skulk/Client/Message.php';
	$msgOptions = array (
		'apikey' => array('APIKEY', 'APIKEY2')
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
	Array
	(
	    [success] => Array
	        (
	            [code] => 200
	            [remaining] => 994
	            [resetdate] => 1303947992
	        )
	
	)

### verify
 
	<?php
	
	require_once 'Skulk/Client.php';
	require_once 'Skulk/Client/Message.php';
	$msgOptions = array (
		'apikey' => 'APIKEY',
		'providerkey' => 'PROVIDERKEY'
	);
	$message = new Skulk_Client_Message($msgOptions);
	$config = array(
	    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
	    'curloptions' => array(
			CURLOPT_FOLLOWLOCATION => true, 
			CURLOPT_SSL_VERIFYPEER => false),
	);
	$client = new Skulk_Client($config);
	$response = $client->verify($message);
	print_r($response->getResult());
 
### retrieveToken
 
	<?php
	require_once 'Skulk/Client.php';
	require_once 'Skulk/Client/Message.php';
	$msgOptions = array (
		'providerkey' => 'PROVIDERKEY'
	);
	$message = new Skulk_Client_Message($msgOptions);
	$config = array(
	    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
	    'curloptions' => array(
			CURLOPT_FOLLOWLOCATION => true, 
			CURLOPT_SSL_VERIFYPEER => false),
	);
	$client = new Skulk_Client($config);
	$response = $client->retrieveToken($message);
	print_r($response->getResult());

### retrieveApikey

	<?php
	require_once 'Skulk/Client.php';
	require_once 'Skulk/Client/Message.php';
	$msgOptions = array (
		'providerkey' => 'PROVIDERKEY',
		'token' => 'TOKEN'
	);
	$message = new Skulk_Client_Message($msgOptions);
	$config = array(
	    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
	    'curloptions' => array(
			CURLOPT_FOLLOWLOCATION => true, 
			CURLOPT_SSL_VERIFYPEER => false),
	);
	$client = new Skulk_Client($config);
	$response = $client->retrieveApikey($message);
	print_r($response->getResult());