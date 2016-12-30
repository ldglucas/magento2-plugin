<?php
namespace Decidir\Client;

class Connection {

	private $connection_timeout;
	private $local_cert;
	
	public function __construct($connection_timeout =  null, $local_cert = null) {
		$this->connection_timeout = $connection_timeout;
		$this->local_cert = file_get_contents($local_cert);
	}
	
	public function setConnectionTimeout($connection_timeout){
		$this->connection_timeout = $connection_timeout;
	}	
	
	public function setLocalCert($local_cert){
		$this->local_cert= file_get_contents($local_cert);
	}

	public function getConnectionTimeout(){
		return $this->connection_timeout;
	}	
	
//	public function setLocalCert(){
//		return $this->local_cert;
//	}
	
	public function toArray() {
		return array(
			'local_cert'=>($this->local_cert), 
			'connection_timeout' => $this->connection_timeout,
		);
	}
}