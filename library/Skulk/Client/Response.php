<?php
/**
 *
 * The response container
 * @author zircote
 * @package Skulk_Client
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
 */
class Skulk_Client_Response
{
    /**
     *
     * Parsed XML Response
     * @var SimpleXMLElement
     */
    public $container;
    /**
     *
     * Parsed Response result container
     * @var array
     */
    public $result = array();
    /**
     *
     * @param string $raw
     */
    public function __construct ($raw)
    {
        if (! function_exists('simplexml_load_string')) {
            require_once 'Skulk/Client/Exception.php';
            throw new Skulk_Client_Exception('SimpleXML support is required');
        }
        if ($this->container = @simplexml_load_string($raw)) {
            foreach ($this->container->children() as $child) {
                if (! in_array((string) $child->getName(),
                array('success', 'retrieve', 'error'))) {
                    $this->result = false;
                    return;
                }
                $this->extract($child);
            }
        } else {
            $this->result = false;
        }
    }
    /**
     *
     * extracts a parsed array for the given SimpleXML object
     * @param SimpleXMLElement $child
     * @return array
     */
    public function extract (SimpleXMLElement $child)
    {
        $index = (string) $child->getName();
        $this->result[$index] = array();
        foreach ($child->attributes() as $k => $v) {
            $this->result[$index][$k] = (string) $v;
        }
        if ($index === 'error') {
            $this->result[$index]['message'] = (string) $child;
            $this->result[$index]['detail'] = $this->getErrorDetail(
            $this->result[$index]['code']);
        }
    }
    /**
     * The final result
     * @return array
     */
    public function getResult ()
    {
        return $this->result;
    }
    /**
     *
     * returns quantity of remaining api calls for current time frame
     * @return integer
     */
    public function getRemaining ()
    {
        if ($this->result && array_key_exists('success', $this->getResult())) {
            return $this->result['success']['remaining'];
        } else {
            return false;
        }
    }
    /**
     *
     * returns a Zend_Date object for the resetdate timestamp
     * @param string|null $format
     * @return Zend_Date
     */
    public function getResetDate ($format = null)
    {
        if ($this->result['success']['resetdate']) {
            require_once 'Zend/Date.php';
            $date = new Zend_Date(null, Zend_Date::DATETIME_FULL);
            return $date->setTimestamp($this->result['success']['resetdate']);
        }
    }
    /**
     *
     * returns a verbose error message based on code provided.
     * @param integer $errorCode
     * @return string
     */
    public function getErrorDetail ($errorCode)
    {
        $errors = array(
        '400' => 'Bad request, the parameters you provided did not validate, see ERRORMESSAGE.',
        '401' => 'Not authorized, the API key given is not valid, and does not correspond to a user.',
        '406' => 'Not acceptable, your IP address has exceeded the API limit.',
        '409' => 'Not approved, the user has yet to approve your retrieve request.',
        '500' => 'Internal server error, something failed to execute properly on the Prowl side.');
        if (array_key_exists($errorCode, $errors)) {
            return $errors[$errorCode];
        } else {
            return false;
        }
    }
}
