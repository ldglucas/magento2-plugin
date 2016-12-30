<?php
namespace Decidir\Data\SplitTransacciones;

class MontoFijo extends AbstractData 
{
	protected $nrocomercio;
	protected $impdist;
	protected $sitedist;
	protected $cuotasdist;

	public function __construct(array $data) {
		$this->setRequiredFields(array(
			"nrocomercio" => array(
				"name" => "SplitTransacciones - Nro Comercio",
				"xml" => "NROCOMERCIO"
			), 
			"impdist" => array(
				"name" => "SplitTransacciones - IMPDIST",
				"xml" => "IMPDIST"
			), 
			"sitedist" => array(
				"name" => "SplitTransacciones - SITEDIST",
				"xml" => "SITEDIST"
			), 
			"cuotasdist" => array(
				"name" => "SplitTransacciones - CUOTASDIST",
				"xml" => "CUOTASDIST"
			), 			
		));
		
		parent::__construct($data);
	}
	
	public function getNrocomercio(){
		return $this->nrocomercio;
	}

	public function setNrocomercio($nrocomercio){
		$this->nrocomercio = $nrocomercio;
	}

	public function getImpdist(){
		return $this->impdist;
	}

	public function setImpdist($impdist){
		$this->impdist = $impdist;
	}

	public function getSitedist(){
		return $this->sitedist;
	}

	public function setSitedist($sitedist){
		$this->sitedist = $sitedist;
	}

	public function getCuotasdist(){
		return $this->cuotasdist;
	}

	public function setCuotasdist($cuotasdist){
		$this->cuotasdist = $cuotasdist;
	}
	
}