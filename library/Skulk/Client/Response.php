<?php


class Skulk_Client_Response {
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
	public function __construct($raw){
		if(!function_exists('simplexml_load_string')){
			require_once 'Skulk/Client/Exception.php';
			throw new Skulk_Client_Exception('SimpleXML support is required');
		}
		if($this->container = @simplexml_load_string($raw)){
			foreach ($this->container->children() as $child) {
				if(!in_array((string) $child->getName(), array('success','retrieve','error'))){
					$this->result = false;
					return;
				}
				$this->extract($child);
			}
		} else {
			$this->result = false;
		}
	}
	
	public function extract(SimpleXMLElement $child){
		$index = (string) $child->getName();
		$this->result[$index] = array();
		foreach ($child->attributes() as $k => $v) {
			$this->result[$index][$k] = (string)$v;
		}
		if($index === 'error'){
			$this->result[$index]['message'] = (string) $child;
		}
	}
	/**
	 * The final result
	 * @return array
	 */
	public function getResult() {
		return $this->result;
	}
	
	public function getRemaining(){
		if($this->result && array_key_exists('success', $this->getResult())){
			return $this->result['success']['remaining'];
		} else {
			return false;
		}
	}
}
