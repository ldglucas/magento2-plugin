<?php
namespace Decidir\Data\Cybersource;

class Passenger extends \Decidir\Data\AbstractData {
	protected $csitpassengeremail;
	protected $csitpassengerfirstname;
	protected $csitpassengerid;
	protected $csitpassengerlastname;
	protected $csitpassengerphone;
	protected $csitpassengerstatus;
	protected $csitpassengertype;

	public function __construct($passengerData) {
		$this->setRequiredFields(array(	
			"csitpassengeremail" => array(
				"name" => "CS - Passenger - CSITPASSENGEREMAIL", 
				"xml" => "CSITPASSENGEREMAIL"
			),
			"csitpassengerfirstname" => array(
				"name" => "CS - Passenger - CSITPASSENGERFIRSTNAME", 
				"xml" => "CSITPASSENGERFIRSTNAME"
			),
			"csitpassengerlastname" => array(
				"name" => "CS - Passenger - CSITPASSENGERLASTNAME", 
				"xml" => "CSITPASSENGERLASTNAME"
			),	
			"csitpassengerphone" => array(
				"name" => "CS - Passenger - CSITPASSENGERPHONE", 
				"xml" => "CSITPASSENGERPHONE"
			),
			"csitpassengerstatus" => array(
				"name" => "CS - Passenger - CSITPASSENGERSTATUS", 
				"xml" => "CSITPASSENGERSTATUS"
			),
			"csitpassengertype" => array(
				"name" => "CS - Passenger - CSITPASSENGERTYPE", 
				"xml" => "CSITPASSENGERTYPE"
			),	
			"csitpassengerid" => array(
				"name" => "CS - Passenger - CSITPASSENGERID", 
				"xml" => "CSITPASSENGERID"
			),			
		));		
		parent::__construct($passengerData);		

	}
	
	public function getCsitpassengeremail(){
		return $this->csitpassengeremail;
	}

	public function setCsitpassengeremail($csitpassengeremail){
		$this->csitpassengeremail = $csitpassengeremail;
	}

	public function getCsitpassengerfirstname(){
		return $this->csitpassengerfirstname;
	}

	public function setCsitpassengerfirstname($csitpassengerfirstname){
		$this->csitpassengerfirstname = $csitpassengerfirstname;
	}

	public function getCsitpassengerid(){
		return $this->csitpassengerid;
	}

	public function setCsitpassengerid($csitpassengerid){
		$this->csitpassengerid = $csitpassengerid;
	}

	public function getCsitpassengerlastname(){
		return $this->csitpassengerlastname;
	}

	public function setCsitpassengerlastname($csitpassengerlastname){
		$this->csitpassengerlastname = $csitpassengerlastname;
	}

	public function getCsitpassengerphone(){
		return $this->csitpassengerphone;
	}

	public function setCsitpassengerphone($csitpassengerphone){
		$this->csitpassengerphone = $csitpassengerphone;
	}

	public function getCsitpassengerstatus(){
		return $this->csitpassengerstatus;
	}

	public function setCsitpassengerstatus($csitpassengerstatus){
		$this->csitpassengerstatus = $csitpassengerstatus;
	}

	public function getCsitpassengertype(){
		return $this->csitpassengertype;
	}

	public function setCsitpassengertype($csitpassengertype){
		$this->csitpassengertype = $csitpassengertype;
	}
}