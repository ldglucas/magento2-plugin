<?php
namespace Decidir\Operation\GetByOperationId;

class Data extends \Decidir\Data\AbstractData {
	
	protected $idsite;
	protected $idtransactionsit;
	
	public function __construct(array $data) {
		$this->setRequiredFields(array(
			"idsite" => array(
				"name" => "IdSite"
			), 
			"idtransactionsit" => array(
				"name" => "IdTransactionSit", 
			), 
		));
		
		parent::__construct($data);

	}
	
	public function getIdsite(){
		return $this->idsite;
	}

	public function setIdsite($idsite){
		$this->idsite = $idsite;
	}

	public function getIdtransactionsit(){
		return $this->idtransactionsit;
	}

	public function setIdtransactionsit($idtransactionsit){
		$this->idtransactionsit = $idtransactionsit;
	}
	
	public function getData() {
		
		$data = new \stdClass();
		$data->IDSITE = $this->idsite;
		$data->IDTRANSACTIONSIT = $this->idtransactionsit;

		return $data;
	}
}