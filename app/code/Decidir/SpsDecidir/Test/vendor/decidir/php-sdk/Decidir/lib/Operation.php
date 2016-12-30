<?php
namespace Decidir;

class Operation extends Client {
	const WSDL = "Operation.wsdl";
	
	public function __construct($endpoint, $header) {
		parent::__construct("Operation", $endpoint, $header);
	}
	
	public function getByOperationId(\Decidir\Operation\GetByOperationId\Data $data){
		$get_status = $this->getClient()->Get($data->getData());
		$operations = json_decode(json_encode($get_status), TRUE);
		return new \Decidir\Operation\GetByOperationId\Response($operations['Operation']);
	}		
}
