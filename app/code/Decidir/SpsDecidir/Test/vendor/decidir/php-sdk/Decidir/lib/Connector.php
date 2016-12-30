<?php
namespace Decidir; 

define('DECIDIR_VERSION','1.0.1');

class Connector
{
	private $header_http = NULL;
	private $end_point = NULL;
	private $end_poit_operation = NULL;

	private $authorize = NULL;
	private $operation = NULL;
	
	private $proxy = NULL;
	private $conn_settings = NULL;
	
	const DECIDIR_ENDPOINT_TEST = 'https://sandbox.decidir.com/services/t/1.1/';
	const DECIDIR_ENDPOINT_TEST_OPERATION = 'https://sandbox.decidir.com/services/t/decidir.net/';

	const DECIDIR_ENDPOINT_PROD = 'https://sps.decidir.com/services/t/1.1/';
	const DECIDIR_ENDPOINT_PROD_OPERATION = 'https://sps.decidir.com/services/t/decidir.com.ar/';

	public function __construct($header_http_array, $endpoint){

		if(($endpoint != self::DECIDIR_ENDPOINT_TEST)&&($endpoint != self::DECIDIR_ENDPOINT_PROD)
			&&($endpoint != self::DECIDIR_ENDPOINT_TEST_OPERATION) && ($endpoint != self::DECIDIR_ENDPOINT_PROD_OPERATION))
			throw new \Decidir\Exception\InvalidEndpointException($endpoint);
		
		if(!isset($header_http_array['Authorization']))
			throw new \Decidir\Exception\RequiredValueException("HTTP_Header Authorization");
		
		$this->end_point = $endpoint;
		if($endpoint == self::DECIDIR_ENDPOINT_TEST) {
			$this->end_point_operation = self::DECIDIR_ENDPOINT_PROD_OPERATION;
		} else {
			$this->end_point_operation = self::DECIDIR_ENDPOINT_TEST_OPERATION;
		}
		$this->header_http = $this->getHeaderHttp($header_http_array);
		
		$this->authorize = new \Decidir\Authorize($this->end_point, $this->header_http);
		$this->operation = new \Decidir\Operation($this->end_point_operation, $this->header_http);
	}
	
	public function getProxy(){
		return $this->proxy;
	}

	public function setProxy(\Decidir\Client\Proxy $proxy){
		$this->proxy = $proxy;
	}

	public function getConnectionSettings(){
		return $this->conn_settings;
	}

	public function setConnectionSettings(\Decidir\Client\Connection $settings){
		$this->conn_settings = $settings;
	}
	
	public function Authorize() {
		if($this->proxy != NULL)
			$this->authorize->setProxy($this->proxy);
		if($this->conn_settings != NULL)
			$this->authorize->setConnectionSettings($this->conn_settings);		
		return $this->authorize;
	}

	public function Operation() {
		if($this->proxy != NULL)
			$this->operation->setProxy($this->proxy);
		if($this->conn_settings != NULL)
			$this->operation->setConnectionSettings($this->conn_settings);		
		return $this->operation;
	}
	
	private function getHeaderHttp($header_http_array){
		$header = "";
		foreach($header_http_array as $key=>$value){
			$header .= "$key: $value\r\n";
		}
		
		return $header;
	}
	
}
