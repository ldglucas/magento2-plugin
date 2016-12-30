<?php
namespace Decidir\Data\Mediopago;

abstract class AbstractData extends \Decidir\Data\AbstractData
{
	protected $medio_pago;
	
	public function __construct($dataMedioPago) {
		$this->setRequiredFields(array(
			"medio_pago" => array(
				"name" => "Medio Pago - Tipo Medio de Pago", 
				"xml" => "MEDIODEPAGO"
			),	
		));
		parent::__construct($dataMedioPago);
	}
	
	public function getMedioPago() {
		return $this->medio_pago;
	}

	public function setMedioPago($medio_pago) {
		$this->medio_pago = $medio_pago;
	}
	
	public function getData() {
		return parent::getXmlData();
	}
}