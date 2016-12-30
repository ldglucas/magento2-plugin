<?php
namespace Decidir\Client;

class Proxy {
	private $host = NULL;
	private $port = NULL;
	private $user = NULL;
	private $pass = NULL;
	
	public function __construct($host = null, $port = null, $user = null, $pass = null){
		$this->host = $host;
		$this->port = $port;
		$this->user = $user;
		$this->pass = $pass;
	}
	
	public function getHost(){
		return $this->host;
	}

	public function setHost($host){
		$this->host = $host;
	}

	public function getPort(){
		return $this->port;
	}

	public function setPort($port){
		$this->port = $port;
	}

	public function getUser(){
		return $this->user;
	}

	public function setUser($user){
		$this->user = $user;
	}

	public function getPass(){
		return $this->pass;
	}

	public function setPass($pass){
		$this->pass = $pass;
	}
	
	public function toArray() {
		return array(
			"proxy_host" => $this->host,
			"proxy_port" => $this->port,
			"proxy_login" => $this->user,
			"proxy_password" => $this->pass,
		);
	}
}