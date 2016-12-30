<?php
namespace Decidir\Data\Mediopago;

class TarjetaCredito extends AbstractData
{
	protected $cuotas;
	protected $bin = null;
	
	public function __construct($tarjetaData) {
		$this->setRequiredFields(array(
			"cuotas" => array(
				"name" => "Tarjeta Credito - Cuotas", 
				"xml" => "CUOTAS"
			),			
		));
		
		$this->setOptionalFields(array(
			"bin" => array(
				"name" => "Tarjeta Credito - BIN", 
				"xml" => "BIN"
			)	
		));		
		parent::__construct($tarjetaData);
	}
	
	public function getCuotas() {
		return $this->cuotas;
	}
	
	public function setCuotas($cuotas) {
		$this->cuotas = $cuotas;
	}

	public function getBin() {
		return $this->bin;
	}
	
	public function setBin($bin) {
		$this->bin = $bin;
	}
}