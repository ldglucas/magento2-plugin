<?php
namespace Decidir\Authorize\GetAuthorizeAnswer;

class Data extends \Decidir\Data\AbstractData {
	
	protected $security;
	protected $merchant;
	
	protected $requestKey;
	protected $answerKey;
	
	public function __construct(array $data) {
		$this->setRequiredFields(array(
			"security" => array(
				"name" => "Security"
			), 
			"merchant" => array(
				"name" => "Merchant - Nro Comercio", 
			), 
			"requestKey" => array(
				"name" => "RequestKey", 
			), 
			"answerKey" => array(
				"name" => "AnswerKey", 	
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

	public function getRequestKey(){
		return $this->requestKey;
	}

	public function setRequestKey($requestKey){
		$this->requestKey = $requestKey;
	}

	public function getAnswerKey(){
		return $this->answerKey;
	}

	public function setAnswerKey($answerKey){
		$this->answerKey = $answerKey;
	}
	
	public function getData() {
		
		$data = new \stdClass();
		$data->Security = $this->security;
		$data->Merchant = $this->merchant;
		$data->RequestKey = $this->requestKey;
		$data->AnswerKey = $this->answerKey;

		return $data;
	}
}