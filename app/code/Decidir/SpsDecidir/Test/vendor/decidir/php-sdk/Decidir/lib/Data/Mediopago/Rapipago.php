<?php
namespace Decidir\Data\Mediopago;

class Rapipago extends AbstractData
{
	protected $cantdiasfechavenc;
	protected $cantdiaspago;
	protected $recargo;
	protected $fechavto;
	protected $cliente;
	
	public function __construct($rapipagoData) {
		$this->setRequiredFields(array(
			"cantdiasfechavenc" => array(
				"name" => "Rapipago - CantDiasFechaVenc", 
				"xml" => "CANTDIASFECHAVENC"
			),	
			"cantdiaspago" => array(
				"name" => "Rapipago - CantDiasPago", 
				"xml" => "CANTDIASPAGO"
			),						
			"recargo" => array(
				"name" => "Rapipago - Recargo", 
				"xml" => "RECARGO"
			),		
			"fechavto" => array(
				"name" => "Rapipago - FechaVto", 
				"xml" => "FECHAVTO"
			),	
			"cliente" => array(
				"name" => "Rapipago - Cliente", 
				"xml" => "CLIENTE"
			),			
		));	
		parent::__construct($rapipagoData);
	}
	
	public function getCantdiasfechavenc() {
		return $this->cantdiasfechavenc;
	}

	public function setCantdiasfechavenc($cantdiasfechavenc) {
		$this->cantdiasfechavenc = $cantdiasfechavenc;
	}

	public function getCantdiaspago() {
		return $this->cantdiaspago;
	}

	public function setCantdiaspago($cantdiaspago) {
		$this->cantdiaspago = $cantdiaspago;
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

	public function getCliente() {
		return $this->cliente;
	}

	public function setCliente($cliente) {
		$this->cliente = $cliente;
	}	
}