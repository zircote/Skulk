# Using Skulk

## About


     skulk  (skulk)
     intr.v. skulked, skulk·ing, skulks
     1. To lie in hiding, as out of cowardice or bad conscience; lurk.
     2. To move about stealthily.
     3. To evade work or obligation; shirk.

 A library for interacting with the prowl api.
 http://www.prowlapp.com/api.php
 
## Installation

#### Install Zend Framework And Skulk:

    pear channel-discover pear.zfcampus.org
    pear install zfcampus/zf
    pear channel-discover pear.zircote.com
    pear install zircote/Skulk-Alpha
 
 
## Methods

### add

 `Skulk_Client::add` allows you to send a message to one or more clients.
 
Setting parameters by methods.

```php
<?php
require_once 'Skulk/Client.php';
require_once 'Skulk/Client/Message.php';
$message = new Skulk_Client_Message();
$message->setApikey('APIKEY')
    ->setEvent('This is a test message')
    ->setPriority(Skulk_Client_Message::PRIORITY_HIGH)
    ->setDescription('just a random test message');
$client = new Skulk_Client();
$response = $client->add($message);
print_r($response->getResult());
/*
Array
(
    [success] => Array
        (
            [code] => 200
            [remaining] => 994
            [resetdate] => 1303947992
        )

)
*/
echo (string) $response->getResetDate();
#>>Apr 28, 2011 6:07:07 PM<<
echo $response->getRemaining();
#>>994<<
```

define the message as an array as well as multiple apikey/destinations:

```php
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
$client = new Skulk_Client();
$response = $client->add($message);
print_r($response->getResult());
/**
Array
(
    [success] => Array
        (
            [code] => 200
            [remaining] => 994
            [resetdate] => 1303947992
        )

)
*/

```

### verify
```php
<?php

require_once 'Skulk/Client.php';
require_once 'Skulk/Client/Message.php';
$msgOptions = array (
    'apikey' => 'APIKEY',
    'providerkey' => 'PROVIDERKEY'
);
$message = new Skulk_Client_Message($msgOptions);
$client = new Skulk_Client();
$response = $client->verify($message);
print_r($response->getResult());
```
 
### retrieveToken

```php
<?php
require_once 'Skulk/Client.php';
require_once 'Skulk/Client/Message.php';
$msgOptions = array (
    'providerkey' => 'PROVIDERKEY'
);
$message = new Skulk_Client_Message($msgOptions);
$client = new Skulk_Client($);
$response = $client->retrieveToken($message);
print_r($response->getResult());

```

### retrieveApikey

```php
<?php
require_once 'Skulk/Client.php';
require_once 'Skulk/Client/Message.php';
$msgOptions = array (
    'providerkey' => 'PROVIDERKEY',
    'token' => 'TOKEN'
);
$message = new Skulk_Client_Message($msgOptions);
$client = new Skulk_Client();
$response = $client->retrieveApikey($message);
print_r($response->getResult());
```

### ZF Provider Config and User

Setting up the Provider

    $ zf enable config.provider Skulk_Tool_ProwlProvider
    Provider/Manifest 'Skulk_Tool_ProwlProvider' was enabled for usage with Zend Tool.

Create the user config file for Zend_Tool

    zf create config

It will be located at `~/.zf.ini` open in your prefered editor and insure the Skulk 
library directory is in the include path then execute the following:

    $ zf ? prowl
    Zend Framework Command Line Console Tool v1.11.5
    Actions supported by provider "Prowl"
      Prowl
        zf addkey prowl keyname apikey
        zf delkey prowl keyname
        zf send-message prowl message priority[=normal] description url

Next add your key(s) with the following command:

    zf addkey prowl <keyname> <prowl-apiKey>

You are now ready to send messages:

    $ zf send-message prowl 'test message' 
    988 messages left until May 8, 2011 5:58:48 PM

### Zend_Log Writer

Setting up the Log Writer for Zend_Log

```php
<?php
$prowl = array(
    'apikey' => '072a7159e...e36ebe57',
    'priority' => Skulk_Client_Message::PRIORITY_EMERGENCY,
    'url' => 'http://www.zircote.com/admin/console',
    'event' => 'Error logging via Prowl with Zend_Log',
    'providerkey' => '072a7159e9e8f......e7765cd11c229e36ebe57'
);
$prowlWriter = Skulk_Log_Writer_Prowl::factory($prowl);
$prowlWriter->addFilter(new Zend_Log_Filter_Priority(Zend_Log::EMERG));
$zendLog = new Zend_Log($this->Skulk_Log_Writer_Prowl);
$zendLog->info('This wont be sent');
$zendLog->emerg('this will be sent');
```
