<?php
namespace Decidir\Data\Mediopago;

class PagoMisCuentas extends AbstractData
{
	protected $fechavto;
	
	public function __construct($pmcData) {
		$this->setRequiredFields(array(					
			"fechavto" => array(
				"name" => "PagoMisCuentas - FechaVto", 
				"xml" => "FECHAVTO"
			),			
		));	
		parent::__construct($pmcData);		
	}

	public function getFechavto() {
		return $this->fechavto;
	}

	public function setFechavto($fechavto) {
		$this->fechavto = $fechavto;
	}
}