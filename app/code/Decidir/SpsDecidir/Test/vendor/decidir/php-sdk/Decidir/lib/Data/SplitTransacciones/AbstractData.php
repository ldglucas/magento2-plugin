<?php

namespace Decidir\Data\SplitTransacciones;

class AbstractData extends \Decidir\Data\AbstractData 
{
	protected $idmodalidad;
	
	public function __construct(array $data) {
		$this->setRequiredFields(array(
			"idmodalidad" => array(
				"name" => "SplitTransacciones - Id Modalidad",
				"xml" => "IDMODALIDAD"
			), 
		));
		
		parent::__construct($data);
	}
	
	public function getIdmodalidad() {
		return $this->idmodalidad;
	}

	public function setIdmodalidad($idmodalidad) {
		$this->idmodalidad = $idmodalidad;
	}
	
	public function getData() {
		return $this->getXmlData();
	}
}