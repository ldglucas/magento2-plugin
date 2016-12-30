<?php
namespace Decidir\Exception;
use \Exception as Exception;

class DecidirException extends Exception {
	protected $data;
	
	public function __construct($message,  $code = 0, \Decidir\Data\AbstractData $data = null, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
		$this->data = $data;
	}
	
	public function getData() {
		return $this->data;
	}
}
