<?php
namespace Decidir;
require 'Client.php';

class Authorize extends Client {
	const WSDL = "Authorize.wsdl";
	
	public function __construct($endpoint, $header) {
		parent::__construct("Authorize", $endpoint, $header);
	}
	
	public function sendAuthorizeRequest(\Decidir\Authorize\SendAuthorizeRequest\Data $sar_data){
		$authorizeRequestResponse = $this->getAuthorizeRequestResponse($sar_data->getData());
		$authorizeRequestResponseValues = $this->parseAuthorizeRequestResponseToArray($authorizeRequestResponse);
		return new \Decidir\Authorize\SendAuthorizeRequest\Response($authorizeRequestResponseValues);
	}
	
	private function getAuthorizeRequestResponse($authorizeRequest){
		$client = $this->getClient();
		$authorizeRequestResponse = $client->SendAuthorizeRequest($authorizeRequest);
		error_log($client->__getLastResponse(),3,__DIR__."/log.log");
		return $authorizeRequestResponse;
	}	
	
	private function parseAuthorizeRequestResponseToArray($authorizeRequestResponse){
		$authorizeRequestResponseOptions = json_decode(json_encode($authorizeRequestResponse), true);
		return $authorizeRequestResponseOptions;
	}
	
	public function getAuthorizeAnswer(\Decidir\Authorize\GetAuthorizeAnswer\Data $gaa_data){
		$authorizeAnswerResponse = $this->getAuthorizeAnswerResponse($gaa_data->getData());
		$authorizeAnswerResponseValues = $this->parseAuthorizeAnswerResponseToArray($authorizeAnswerResponse);
		return new \Decidir\Authorize\GetAuthorizeAnswer\Response($authorizeAnswerResponseValues);
	}
	
	private function getAuthorizeAnswerResponse($authorizeAnswer){
		$authorizeAnswer = $this->getClient()->GetAuthorizeAnswer($authorizeAnswer);
		return $authorizeAnswer;
	}
	
	private function parseAuthorizeAnswerResponseToArray($authorizeAnswerResponse){
		$authorizeAnswerResponseOptions = json_decode(json_encode($authorizeAnswerResponse), true);
		return $authorizeAnswerResponseOptions;
	}
	
	public function execute(\Decidir\Authorize\Execute\Data $data){
		$get_execute = $this->getClient()->Execute($data->getData());
		return new \Decidir\Authorize\Execute\Response(json_decode(json_encode($get_execute), TRUE));
	}
	
	
}