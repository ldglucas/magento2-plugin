<?php
namespace Decidir\Authorize\Execute\Devolucion;

class Parcial extends Data {
	protected $monto;
	
	public function __construct(array $data) {

		$this->setRequiredFields(array(
			"monto" => array(
				"name" => "Monto", 
				"xml" => "MONTO"
			), 
		));
		
		parent::__construct($data);

	}	
	
	public function getMonto(){
		return $this->monto;
	}

	public function setMonto($monto){
		$this->monto = $monto;
	}	
}