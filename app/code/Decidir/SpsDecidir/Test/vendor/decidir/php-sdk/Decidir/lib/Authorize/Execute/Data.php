<?php
namespace Decidir\Authorize\Execute;

class Data extends \Decidir\Data\AbstractData {
	
	protected $security;
	protected $merchant;
	protected $operation;
	protected $encoding_method = "XML";
	
	public function __construct(array $data) {
		$this->setRequiredFields(array(
			"security" => array(
				"name" => "Security"
			), 
			"merchant" => array(
				"name" => "Merchant - Nro Comercio", 
			), 
			"operation" => array(
				"name" => "Operation", 
			), 
		));
		
		$this->setOptionalFields(array(
			"encoding_method" => array(
				"name" => "Encoding Method", 	
			), 		
		));
		
		parent::__construct($data);

	}
	
	public function getSecurity(){
		return $this->security;
	}

	public function setSecurity($security){
		$this->security = $security;
	}

	public function getMerchant(){
		return $this->merchant;
	}

	public function setMerchant($merchant){
		$this->merchant = $merchant;
	}

	public function getOperation(){
		return $this->operation;
	}

	public function setOperation($operation){
		$this->operation = $operation;
	}

	public function getEncoding_method(){
		return $this->encoding_method;
	}

	public function setEncoding_method($encoding_method){
		$this->encoding_method = $encoding_method;
	}
	
	public function getData() {
		$output = $this->getXmlData();
		$output = "<Request>".$output."</Request>";
		
		$data = new \stdClass();
		$data->Security = $this->security;
		$data->Merchant = $this->merchant;
		$data->Operation = $this->operation;
		$data->EncodingMethod = $this->encoding_method;
		$data->Payload = $output;

		return $data;
	}
}