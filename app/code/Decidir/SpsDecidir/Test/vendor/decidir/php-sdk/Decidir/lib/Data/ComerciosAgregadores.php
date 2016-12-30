<?php

namespace Decidir\Data;

class ComerciosAgregadores extends \Decidir\Data\AbstractData 
{
	protected $aindicador;
	protected $adocumento;
	protected $afactpagar = null;
	protected $afactdevol = null;
	protected $anombrecom;
	protected $adomiciliocomercio;
	protected $anropuerta;
	protected $acodpostal;
	protected $arubro;
	protected $acodcanal = null;
	protected $acodgeografico = null;
	
	public function __construct(array $data) {
		
		$this->setRequiredFields(array(
			"aindicador" => array(
				"name" => "Agregador - AINDICADOR",
				"xml" => "AINDICADOR"
			), 
			"adocumento" => array(
				"name" => "Agregador - ADOCUMENTO",
				"xml" => "ADOCUMENTO"
			), 
			"anombrecom" => array(
				"name" => "Agregador - ANOMBRECOM",
				"xml" => "ANOMBRECOM"
			), 
			"adomiciliocomercio" => array(
				"name" => "Agregador - ADOMICILIOCOMERCIO",
				"xml" => "ADOMICILIOCOMERCIO"
			), 
			"anropuerta" => array(
				"name" => "Agregador - ANROPUERTA",
				"xml" => "ANROPUERTA"
			), 
			"acodpostal" => array(
				"name" => "Agregador - ACODPOSTAL",
				"xml" => "ACODPOSTAL"
			), 
			"arubro" => array(
				"name" => "Agregador - ARUBRO",
				"xml" => "ARUBRO"
			), 
		));
		
		$this->setOptionalFields(array(
			"afactpagar" => array(
				"name" => "Agregador - AFACTPAGAR",
				"xml" => "AFACTPAGAR"
			), 
			"afactdevol" => array(
				"name" => "Agregador - AFACTDEVOL",
				"xml" => "AFACTDEVOL"
			), 
			"acodcanal" => array(
				"name" => "Agregador - ACODCANAL",
				"xml" => "ACODCANAL"
			), 
			"acodgeografico" => array(
				"name" => "Agregador - ACODGEOGRAFICO",
				"xml" => "ACODGEOGRAFICO"
			)
		));
		
		parent::__construct($data);		
	}
	
	public function getAindicador(){
		return $this->aindicador;
	}

	public function setAindicador($aindicador){
		$this->aindicador = $aindicador;
	}

	public function getAdocumento(){
		return $this->adocumento;
	}

	public function setAdocumento($adocumento){
		$this->adocumento = $adocumento;
	}

	public function getAfactpagar(){
		return $this->afactpagar;
	}

	public function setAfactpagar($afactpagar){
		$this->afactpagar = $afactpagar;
	}

	public function getAfactdevol(){
		return $this->afactdevol;
	}

	public function setAfactdevol($afactdevol){
		$this->afactdevol = $afactdevol;
	}

	public function getAnombrecom(){
		return $this->anombrecom;
	}

	public function setAnombrecom($anombrecom){
		$this->anombrecom = $anombrecom;
	}

	public function getAdomiciliocomercio(){
		return $this->adomiciliocomercio;
	}

	public function setAdomiciliocomercio($adomiciliocomercio){
		$this->adomiciliocomercio = $adomiciliocomercio;
	}

	public function getAnropuerta(){
		return $this->anropuerta;
	}

	public function setAnropuerta($anropuerta){
		$this->anropuerta = $anropuerta;
	}

	public function getAcodpostal(){
		return $this->acodpostal;
	}

	public function setAcodpostal($acodpostal){
		$this->acodpostal = $acodpostal;
	}

	public function getArubro(){
		return $this->arubro;
	}

	public function setArubro($arubro){
		$this->arubro = $arubro;
	}

	public function getAcodcanal(){
		return $this->acodcanal;
	}

	public function setAcodcanal($acodcanal){
		$this->acodcanal = $acodcanal;
	}

	public function getAcodgeografico(){
		return $this->acodgeografico;
	}

	public function setAcodgeografico($acodgeografico){
		$this->acodgeografico = $acodgeografico;
	}
	
	public function getData() {
		return $this->getXmlData();
	}
	
}