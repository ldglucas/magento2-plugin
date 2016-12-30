<?php
namespace Decidir\Data\Cybersource;

class Travel extends AbstractData
{
	protected $csdmcompleteroute;
	protected $csdmjourneytype;
	protected $csdmdeparturedatetime;
	protected $csadnumberofpassengers;
	protected $csmdd17;
	protected $csmdd18;
	protected $csmdd19 = null;
	protected $csmdd20 = null;
	protected $csmdd21 = null;
	protected $csmdd22 = null;
	protected $csmdd23 = null;
	protected $csmdd24 = null;
	protected $csmdd25 = null;
	protected $csmdd26 = null;
	protected $csmdd27 = null;
	
	protected $passenger_data = null;
	
	public function __construct($travelData, $passengersData) {
		$this->passenger_data = new Collection();
		
		$this->setRequiredFields(array(	
			"csdmcompleteroute" => array(
				"name" => "CS - Travel - CSDMCOMPLETEROUTE", 
				"xml" => "CSDMCOMPLETEROUTE"
			),
			"csdmjourneytype" => array(
				"name" => "CS - Travel - CSDMJOURNEYTYPE", 
				"xml" => "CSDMJOURNEYTYPE"
			),
			"csdmdeparturedatetime" => array(
				"name" => "CS - Travel - CSDMDEPARTUREDATETIME", 
				"xml" => "CSDMDEPARTUREDATETIME"
			),
			"csadnumberofpassengers" => array(
				"name" => "CS - Travel - CSADNUMBEROFPASSENGERS", 
				"xml" => "CSADNUMBEROFPASSENGERS"
			),	
			"csmdd17" => array(
				"name" => "CS - Travel - CSMDD17", 
				"xml" => "CSMDD17"
			),
			"csmdd18" => array(
				"name" => "CS - Travel - CSMDD18", 
				"xml" => "CSMDD18"
			),		
		));
		
		$this->setOptionalFields(array(
			"csmdd19" => array(
				"name" => "CS - Travel - CSMDD19", 
				"xml" => "CSMDD19"
			),	
			"csmdd20" => array(
				"name" => "CS - Travel - CSMDD20", 
				"xml" => "CSMDD20"
			),	
			"csmdd21" => array(
				"name" => "CS - Travel - CSMDD21", 
				"xml" => "CSMDD21"
			),	
			"csmdd22" => array(
				"name" => "CS - Travel - CSMDD22", 
				"xml" => "CSMDD22"
			),	
			"csmdd23" => array(
				"name" => "CS - Travel - CSMDD23", 
				"xml" => "CSMDD23"
			),	
			"csmdd24" => array(
				"name" => "CS - Travel - CSMDD24", 
				"xml" => "CSMDD24"
			),	
			"csmdd25" => array(
				"name" => "CS - Travel - CSMDD25", 
				"xml" => "CSMDD25"
			),	
			"csmdd26" => array(
				"name" => "CS - Travel - CSMDD26", 
				"xml" => "CSMDD26"
			),	
			"csmdd27" => array(
				"name" => "CS - Travel - CSMDD27", 
				"xml" => "CSMDD27"
			),				
		));		
		
		parent::__construct($travelData);		
		
		foreach($passengersData as $passenger) {
			$this->passenger_data[] = new Passenger($passenger);
		}
	}
	
	public function getCsdmcompleteroute(){
		return $this->csdmcompleteroute;
	}

	public function setCsdmcompleteroute($csdmcompleteroute){
		$this->csdmcompleteroute = $csdmcompleteroute;
	}

	public function getCsdmjourneytype(){
		return $this->csdmjourneytype;
	}

	public function setCsdmjourneytype($csdmjourneytype){
		$this->csdmjourneytype = $csdmjourneytype;
	}

	public function getCsdmdeparturedatetime(){
		return $this->csdmdeparturedatetime;
	}

	public function setCsdmdeparturedatetime($csdmdeparturedatetime){
		$this->csdmdeparturedatetime = $csdmdeparturedatetime;
	}

	public function getCsadnumberofpassengers(){
		return $this->csadnumberofpassengers;
	}

	public function setCsadnumberofpassengers($csadnumberofpassengers){
		$this->csadnumberofpassengers = $csadnumberofpassengers;
	}

	public function getCsmdd17(){
		return $this->csmdd17;
	}

	public function setCsmdd17($csmdd17){
		$this->csmdd17 = $csmdd17;
	}

	public function getCsmdd18(){
		return $this->csmdd18;
	}

	public function setCsmdd18($csmdd18){
		$this->csmdd18 = $csmdd18;
	}

	public function getCsmdd19(){
		return $this->csmdd19;
	}

	public function setCsmdd19($csmdd19){
		$this->csmdd19 = $csmdd19;
	}

	public function getCsmdd20(){
		return $this->csmdd20;
	}

	public function setCsmdd20($csmdd20){
		$this->csmdd20 = $csmdd20;
	}

	public function getCsmdd21(){
		return $this->csmdd21;
	}

	public function setCsmdd21($csmdd21){
		$this->csmdd21 = $csmdd21;
	}

	public function getCsmdd22(){
		return $this->csmdd22;
	}

	public function setCsmdd22($csmdd22){
		$this->csmdd22 = $csmdd22;
	}

	public function getCsmdd23(){
		return $this->csmdd23;
	}

	public function setCsmdd23($csmdd23){
		$this->csmdd23 = $csmdd23;
	}

	public function getCsmdd24(){
		return $this->csmdd24;
	}

	public function setCsmdd24($csmdd24){
		$this->csmdd24 = $csmdd24;
	}

	public function getCsmdd25(){
		return $this->csmdd25;
	}

	public function setCsmdd25($csmdd25){
		$this->csmdd25 = $csmdd25;
	}

	public function getCsmdd26(){
		return $this->csmdd26;
	}

	public function setCsmdd26($csmdd26){
		$this->csmdd26 = $csmdd26;
	}

	public function getCsmdd27(){
		return $this->csmdd27;
	}

	public function setCsmdd27($csmdd27){
		$this->csmdd27 = $csmdd27;
	}	

	public function getData() {
		$output  = parent::getXmlData();
		$output .= $this->products_data->getData("passanger");
		return $output;
	}	
}