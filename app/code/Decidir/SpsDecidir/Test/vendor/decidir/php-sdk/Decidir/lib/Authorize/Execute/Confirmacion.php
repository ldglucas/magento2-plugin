<?php
namespace Decidir\Authorize\Execute;

class Confirmacion extends Data {
	protected $nro_operacion;
	
	public function __construct(array $data) {
		
		$data = array_merge($data, array("operation" => "Confirmacion"));
		
		$this->setRequiredFields(array(
			"merchant" => array(
				"name" => "Merchant - Nro Comercio", 
				"xml" => "NROCOMERCIO"
			), 
			"nro_operacion" => array(
				"name" => "Nro Operacion", 
				"xml" => "NROOPERACION"
			),
		));
		
		parent::__construct($data);

	}

	public function getNro_operacion(){
		return $this->nro_operacion;
	}

	public function setNro_operacion($nro_operacion){
		$this->nro_operacion = $nro_operacion;
	}
	
}