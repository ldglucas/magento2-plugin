<?php
namespace Decidir\Data\Cybersource;

class Retail extends AbstractData
{
	protected $csstcity;
	protected $csstcountry;
	protected $csstemail;
	protected $csstfirstname;
	protected $csstlastname;
	protected $csstphonenumber;
	protected $csstpostalcode;
	protected $csststate;
	protected $csststreet1;
	protected $csststreet2 = null;
	protected $csmdd12 = null;
	protected $csmdd13 = null;
	protected $csmdd14 = null;
	protected $csmdd15 = null;
	protected $csmdd16 = null;
	
	protected $products_data = null;
	
	public function __construct($retailData, $productsData) {
		$this->products_data = new Collection();
		
		$this->setRequiredFields(array(	
			"csstcity" => array(
				"name" => "CS - Retail - CSSTCITY", 
				"xml" => "CSSTCITY"
			),
			"csstcountry" => array(
				"name" => "CS - Retail - CSSTCOUNTRY", 
				"xml" => "CSSTCOUNTRY"
			),
			"csstemail" => array(
				"name" => "CS - Retail - CSSTEMAIL", 
				"xml" => "CSSTEMAIL"
			),
			"csstfirstname" => array(
				"name" => "CS - Retail - CSSTFIRSTNAME", 
				"xml" => "CSSTFIRSTNAME"
			),	
			"csstlastname" => array(
				"name" => "CS - Retail - CSSTLASTNAME", 
				"xml" => "CSSTLASTNAME"
			),
			"csstphonenumber" => array(
				"name" => "CS - Retail - CSSTPHONENUMBER", 
				"xml" => "CSSTPHONENUMBER"
			),
			"csstpostalcode" => array(
				"name" => "CS - Retail - CSSTPOSTALCODE", 
				"xml" => "CSSTPOSTALCODE"
			),
			"csststate" => array(
				"name" => "CS - Retail - CSSTSTATE", 
				"xml" => "CSSTSTATE"
			),
			"csststreet1" => array(
				"name" => "CS - Retail - CSSTSTREET1", 
				"xml" => "CSSTSTREET1"
			),					
		));
		
		$this->setOptionalFields(array(
			"csststreet2" => array(
				"name" => "CS - Retail - CSSTSTREET2", 
				"xml" => "CSSTSTREET2"
			),	
			"csmdd12" => array(
				"name" => "CS - Retail - CSMDD12", 
				"xml" => "CSMDD12"
			),	
			"csmdd13" => array(
				"name" => "CS - Retail - CSMDD13", 
				"xml" => "CSMDD13"
			),	
			"csmdd14" => array(
				"name" => "CS - Retail - CSMDD14", 
				"xml" => "CSMDD14"
			),	
			"csmdd15" => array(
				"name" => "CS - Retail - CSMDD15", 
				"xml" => "CSMDD15"
			),	
			"csmdd16" => array(
				"name" => "CS - Retail - CSMDD16", 
				"xml" => "CSMDD16"
			),				
		));		
		
		parent::__construct($retailData);

		foreach($productsData as $product) {
			$this->products_data[] = new Product($product);
		}
	}
	
	public function getCsstcity() {
		return $this->csstcity;
	}

	public function setCsstcity($csstcity) {
		$this->csstcity = $csstcity;
	}

	public function getCsstcountry() {
		return $this->csstcountry;
	}

	public function setCsstcountry($csstcountry) {
		$this->csstcountry = $csstcountry;
	}

	public function getCsstemail() {
		return $this->csstemail;
	}

	public function setCsstemail($csstemail) {
		$this->csstemail = $csstemail;
	}

	public function getCsstfirstname() {
		return $this->csstfirstname;
	}

	public function setCsstfirstname($csstfirstname) {
		$this->csstfirstname = $csstfirstname;
	}

	public function getCsstlastname() {
		return $this->csstlastname;
	}

	public function setCsstlastname($csstlastname) {
		$this->csstlastname = $csstlastname;
	}

	public function getCsstphonenumber() {
		return $this->csstphonenumber;
	}

	public function setCsstphonenumber($csstphonenumber) {
		$this->csstphonenumber = $csstphonenumber;
	}

	public function getCsstpostalcode() {
		return $this->csstpostalcode;
	}

	public function setCsstpostalcode($csstpostalcode) {
		$this->csstpostalcode = $csstpostalcode;
	}

	public function getCsststate() {
		return $this->csststate;
	}

	public function setCsststate($csststate) {
		$this->csststate = $csststate;
	}

	public function getCsststreet1() {
		return $this->csststreet1;
	}

	public function setCsststreet1($csststreet1) {
		$this->csststreet1 = $csststreet1;
	}

	public function getCsststreet2() {
		return $this->csststreet2;
	}

	public function setCsststreet2($csststreet2) {
		$this->csststreet2 = $csststreet2;
	}

	public function getCsmdd12() {
		return $this->csmdd12;
	}

	public function setCsmdd12($csmdd12) {
		$this->csmdd12 = $csmdd12;
	}

	public function getCsmdd13() {
		return $this->csmdd13;
	}

	public function setCsmdd13($csmdd13) {
		$this->csmdd13 = $csmdd13;
	}

	public function getCsmdd14() {
		return $this->csmdd14;
	}

	public function setCsmdd14($csmdd14) {
		$this->csmdd14 = $csmdd14;
	}

	public function getCsmdd15() {
		return $this->csmdd15;
	}

	public function setCsmdd15($csmdd15) {
		$this->csmdd15 = $csmdd15;
	}

	public function getCsmdd16() {
		return $this->csmdd16;
	}

	public function setCsmdd16($csmdd16) {
		$this->csmdd16 = $csmdd16;
	}	

}