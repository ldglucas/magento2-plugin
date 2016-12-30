<?php
namespace Decidir;
class Client {

	private $service = "";
	private $endpoint = "";
	private $header_http = "";

	private $proxy;
	private $conn_settings;

	const WSDL = "";

	public function __construct($service, $endpoint, $header) {
		$this->service = $service;
		$this->endpoint = $endpoint;
		$this->header_http = $header;
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

	protected function getClient(){
		$local_wsdl = dirname(__FILE__) . "/" . static::WSDL;
		$local_end_point = $this->endpoint.$this->service;
		$context = array('http' =>
			array(
				'header'  => $this->header_http
			)
		);

		$options = array(
			'stream_context' => stream_context_create($context),
			'location' => $local_end_point,
			'encoding' => 'UTF-8',
			'trace' => 1,
		);

		if($this->getProxy() != null) {
			$options = array_merge($options, $this->getProxy()->toArray());
		}

		if($this->getConnectionSettings() != null) {
			$options = array_merge($options, $this->getConnectionSettings()->toArray());
		}

		// Fix bug #49853 - https://bugs.php.net/bug.php?id=49853
		if(version_compare(PHP_VERSION, '5.3.8') == -1) {
			$clientSoap = new \Decidir\Client\CurlSoap($local_wsdl, $options);
			$clientSoap->setCustomHeaders($context);
			return $clientSoap;
		}

		$clientSoap = new \SoapClient($local_wsdl, $options);
		return $clientSoap;
	}

}