<?php
namespace Decidir\Data\Mediopago;

class PagoFacil extends AbstractData
{
	protected $recargo;
	protected $fechavto;
	protected $fechavto2;
	
	public function __construct($pagofacilData) {
		$this->setRequiredFields(array(					
			"recargo" => array(
				"name" => "PagoFacil - Recargo", 
				"xml" => "RECARGO"
			),		
			"fechavto" => array(
				"name" => "PagoFacil - FechaVto", 
				"xml" => "FECHAVTO"
			),	
			"fechavto2" => array(
				"name" => "PagoFacil - FechaVto2", 
				"xml" => "FECHAVTO2"
			),			
		));	
		parent::__construct($pagofacilData);
	}

	public function getRecargo() {
		return $this->recargo;
	}

	public function setRecargo($recargo) {
		$this->recargo = $recargo;
	}

	public function getFechavto() {
		return $this->fechavto;
	}

	public function setFechavto($fechavto) {
		$this->fechavto = $fechavto;
	}

	public function getFechavto2() {
		return $this->fechavto2;
	}

	public function setFechavto2($fechavto2) {
		$this->fechavto2 = $fechavto2;
	}
}