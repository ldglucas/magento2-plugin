<?php
namespace Decidir\Authorize\Execute\Devolucion;

require_once __DIR__.'/../Data.php';

class Data extends \Decidir\Authorize\Execute\Data {
	protected $nro_operacion;
	
	public function __construct(array $data) {
		
		$data = array_merge($data, array("operation" => "Devolucion"));
		
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