<?php
namespace Decidir\Authorize\Execute;

require __DIR__.'../../../Data/Response.php';

class Response extends \Decidir\Data\Response {
	
	protected $AuthorizationKey;
	protected $EncodingMethod;
	
	public function __construct(array $data) {
		
		$this->setOptionalFields(array(
			"AuthorizationKey" => array(
				"name" => "AuthorizationKey"
			),
			"EncodingMethod" => array(
				"name" => "EncodingMethod"
			), 	
		));
		
		parent::__construct($data);
		
	}

	public function getAuthorizationKey(){
		return $this->AuthorizationKey;
	}

	public function getEncodingMethod(){
		return $this->EncodingMethod;
	}
	
}