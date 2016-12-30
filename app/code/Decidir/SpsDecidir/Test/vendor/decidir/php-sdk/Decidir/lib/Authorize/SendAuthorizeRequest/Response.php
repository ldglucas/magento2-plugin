<?php
namespace Decidir\Authorize\SendAuthorizeRequest;

class Response extends \Decidir\Data\Response {

	protected $URL_Request;
	protected $RequestKey;
	protected $PublicRequestKey;
	
	public function __construct(array $data) {
		
		$this->setOptionalFields(array(
			"URL_Request" => array(
				"name" => "URL_Request"
			),
			"RequestKey" => array(
				"name" => "RequestKey"
			), 		
			"PublicRequestKey" => array(
				"name" => "PublicRequestKey"
			), 				
		));
		
		parent::__construct($data);
		
	}

	public function getURL_Request(){
		return $this->URL_Request;
	}

	public function getRequestKey(){
		return $this->RequestKey;
	}

	public function getPublicRequestKey(){
		return $this->PublicRequestKey;
	}
	
}