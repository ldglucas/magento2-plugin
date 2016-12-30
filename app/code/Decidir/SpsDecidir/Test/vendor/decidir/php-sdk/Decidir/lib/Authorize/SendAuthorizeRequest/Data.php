<?php
namespace Decidir\Authorize\SendAuthorizeRequest;

class Data extends \Decidir\Data\AbstractData {
	
	protected $security;
	protected $encoding_method = "XML";
	protected $merchant;
	
	protected $nro_comercio;
	protected $nro_operacion;
	protected $monto;
	protected $email_cliente;
	protected $tokenizar;
	
	protected $mediopago_data = null;	
	protected $cybersource_data = null;
	protected $agregadores_data = null;
	protected $split_data = null;
	
	public function __construct(array $data) {
		$this->setRequiredFields(array(
			"security" => array(
				"name" => "Security"
			), 
			"merchant" => array(
				"name" => "Merchant - Nro Comercio", 
				"xml" => "NROCOMERCIO"
			), 
			"nro_operacion" => array(
				"name" => "Nro Operación", 
				"xml" => "NROOPERACION"
			), 
			"monto" => array(
				"name" => "Monto", 
				"xml" => "MONTO"		
			), 
			"email_cliente" => array(
				"name" => "Email Cliente", 
				"xml" => "EMAILCLIENTE"		
			),
			"tokenizar" => array(
				"name" => "Tokenizar",
				"xml" => "TOKENIZAR"
			)
		));
		
		$this->setOptionalFields(array(
			"encoding_method" => array(
				"name" => "Encoding Method"
			)
		));
		
		parent::__construct($data);

		$this->nro_comercio 	= $data['merchant'];
		$this->monto 			= number_format($data['monto'],2,".","");	
	}
	
	public function getSecurity() {
		return $this->security;
	}

	public function getEncodingMethod() {
		return $this->encoding_method;
	}

	public function getMerchant() {
		return $this->merchant;
	}

	public function getNroComercio() {
		return $this->nro_comercio;
	}

	public function getNroOperacion() {
		return $this->nro_operacion;
	}

	public function getMonto() {
		return $this->monto;
	}

	public function getEmailCliente() {
		return $this->email_cliente;
	}
	
	public function setSecurity($security) {
		$this->security = $security;
	}

	public function setEncodingMethod($encoding_method) {
		$this->encoding_method = $encoding_method;
	}

	public function setMerchant($merchant) {
		$this->merchant = $merchant;
	}

	public function setNroComercio($nro_comercio) {
		$this->nro_comercio = $nro_comercio;
	}

	public function setNroOperacion($nro_operacion) {
		$this->nro_operacion = $nro_operacion;
	}

	public function setMonto($monto) {
		$this->monto = $monto;
	}

	public function setEmailCliente($email_cliente) {
		$this->email_cliente = $email_cliente;
	}		
	
	public function getMedioPago() {
		return $this->mediopago_data;
	}
	
	public function setMedioPago(\Decidir\Data\Mediopago\AbstractData $mediopago_data) {
		$this->mediopago_data = $mediopago_data;
	}
	
	public function getCybersourceData() {
		return $this->cybersource_data;
	}
	
	public function setCybersourceData(\Decidir\Data\Cybersource\AbstractData $cybersource_data) {
		$this->cybersource_data = $cybersource_data;
	}
	
	public function getAgregadoresData() {
		return $this->agregadores_data;
	}
	
	public function setAgregadoresData(\Decidir\Data\ComerciosAgregadores $agregadores_data) {
		$this->agregadores_data = $agregadores_data;
	}
	
	public function getSplitData() {
		return $this->split_data;
	}
	
	public function setSplitData(\Decidir\Data\SplitTransacciones\AbstractData $split_data) {
		$this->split_data = $split_data;
	}
	
	public function getData() {
		$output  = $this->getXmlData();
		
		if($this->mediopago_data != null)
			$output .= $this->mediopago_data->getData();
		if($this->cybersource_data != null)
			$output .= $this->cybersource_data->getData();
		if($this->agregadores_data != null)
			$output .= $this->agregadores_data->getData();
		if($this->split_data != null)
			$output .= $this->split_data->getData();
		
		$output = '<Request>'.$output.'</Request>';
		
		$data = new \stdClass();
		$data->Security = $this->security;
		$data->Merchant = $this->merchant;
		$data->EncodingMethod = $this->encoding_method;
		$data->Payload = $output;

		return $data;
	}
}