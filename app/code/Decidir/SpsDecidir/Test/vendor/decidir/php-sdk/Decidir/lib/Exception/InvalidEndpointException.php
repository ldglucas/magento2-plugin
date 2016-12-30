<?php

namespace Decidir\Exception;

class InvalidEndpointException extends \Decidir\Exception\DecidirException {
	
	public function __construct($data) {
		$message = "Endpoint no valido: " . $data;
		$code = 777787;
		parent::__construct($message, $code);
	}
}