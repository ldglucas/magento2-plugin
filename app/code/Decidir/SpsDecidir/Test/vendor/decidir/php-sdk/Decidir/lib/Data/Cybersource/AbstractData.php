<?php
namespace Decidir\Data\Cybersource;

abstract class AbstractData extends \Decidir\Data\AbstractData
{
	protected $device_fingerprint;
	protected $csbtcity;
	protected $csbtcountry;
	protected $csbtcustomerid;
	protected $csbtipaddress;
	protected $csbtemail;
	protected $csbtfirstname;
	protected $csbtlastname;
	protected $csbtphonenumber;
	protected $csbtpostalcode;
	protected $csbtstate;
	protected $csbtstreet1;
	protected $csbtstreet2 = null;
	protected $csptcurrency;
	protected $csptgrandtotalamount;
	protected $csmdd6 = null;
	protected $csmdd7 = null;
	protected $csmdd8 = null;
	protected $csmdd9 = null;	
	protected $csmdd10 = null;
	protected $csmdd11 = null;
	
	public function __construct($dataCS) {
		$this->setRequiredFields(array(
			"device_fingerprint" => array(
				"name" => "CS - General - Device Fingerprint", 
				"xml" => "CSDEVICEFINGERPRINTID"
			),	
			"csbtcity" => array(
				"name" => "CS - General - CSBTCITY", 
				"xml" => "CSBTCITY"
			),
			"csbtcountry" => array(
				"name" => "CS - General - CSBTCOUNTRY", 
				"xml" => "CSBTCOUNTRY"
			),
			"csbtcustomerid" => array(
				"name" => "CS - General - CSBTCUSTOMERID", 
				"xml" => "CSBTCUSTOMERID"
			),
			"csbtipaddress" => array(
				"name" => "CS - General - CSBTIPADDRESS", 
				"xml" => "CSBTIPADDRESS"
			),
			"csbtemail" => array(
				"name" => "CS - General - CSBTEMAIL", 
				"xml" => "CSBTEMAIL"
			),
			"csbtfirstname" => array(
				"name" => "CS - General - CSBTFIRSTNAME", 
				"xml" => "CSBTFIRSTNAME"
			),	
			"csbtlastname" => array(
				"name" => "CS - General - CSBTLASTNAME", 
				"xml" => "CSBTLASTNAME"
			),
			"csbtphonenumber" => array(
				"name" => "CS - General - CSBTPHONENUMBER", 
				"xml" => "CSBTPHONENUMBER"
			),
			"csbtpostalcode" => array(
				"name" => "CS - General - CSBTPOSTALCODE", 
				"xml" => "CSBTPOSTALCODE"
			),
			"csbtstate" => array(
				"name" => "CS - General - CSBTSTATE", 
				"xml" => "CSBTSTATE"
			),
			"csbtstreet1" => array(
				"name" => "CS - General - CSBTSTREET1", 
				"xml" => "CSBTSTREET1"
			),	
			"csptcurrency" => array(
				"name" => "CS - General - CSPTCURRENCY", 
				"xml" => "CSPTCURRENCY"
			),
			"csptgrandtotalamount" => array(
				"name" => "CS - General - CSPTGRANDTOTALAMOUNT", 
				"xml" => "CSPTGRANDTOTALAMOUNT"
			),				
		));
		
		$this->setOptionalFields(array(
			"csbtstreet2" => array(
				"name" => "CS - General - CSBTSTREET2", 
				"xml" => "CSBTSTREET2"
			),	
			"csmdd6" => array(
				"name" => "CS - General - CSMDD6", 
				"xml" => "CSMDD6"
			),	
			"csmdd7" => array(
				"name" => "CS - General - CSMDD7", 
				"xml" => "CSMDD7"
			),	
			"csmdd8" => array(
				"name" => "CS - General - CSMDD8", 
				"xml" => "CSMDD8"
			),	
			"csmdd9" => array(
				"name" => "CS - General - CSMDD9", 
				"xml" => "CSMDD9"
			),	
			"csmdd10" => array(
				"name" => "CS - General - CSMDD10", 
				"xml" => "CSMDD10"
			),	
			"csmdd11" => array(
				"name" => "CS - General - CSMDD11", 
				"xml" => "CSMDD11"
			),				
		));		
		
		parent::__construct($dataCS);
	}	
	
	public function getDeviceFingerprint() {
		return $this->device_fingerprint;
	}

	public function setDeviceFingerprint($devfp) {
		$this->device_fingerprint = $devfp;
	}

	public function getCsbtcity() {
		return $this->csbtcity;
	}

	public function setCsbtcity($csbtcity) {
		$this->csbtcity = $csbtcity;
	}

	public function getCsbtcountry() {
		return $this->csbtcountry;
	}

	public function setCsbtcountry($csbtcountry) {
		$this->csbtcountry = $csbtcountry;
	}

	public function getCsbtcustomerid() {
		return $this->csbtcustomerid;
	}

	public function setCsbtcustomerid($csbtcustomerid) {
		$this->csbtcustomerid = $csbtcustomerid;
	}

	public function getCsbtipaddress() {
		return $this->csbtipaddress;
	}

	public function setCsbtipaddress($csbtipaddress) {
		$this->csbtipaddress = $csbtipaddress;
	}

	public function getCsbtemail() {
		return $this->csbtemail;
	}

	public function setCsbtemail($csbtemail) {
		$this->csbtemail = $csbtemail;
	}

	public function getCsbtfirstname() {
		return $this->csbtfirstname;
	}

	public function setCsbtfirstname($csbtfirstname) {
		$this->csbtfirstname = $csbtfirstname;
	}

	public function getCsbtlastname() {
		return $this->csbtlastname;
	}

	public function setCsbtlastname($csbtlastname) {
		$this->csbtlastname = $csbtlastname;
	}

	public function getCsbtphonenumber() {
		return $this->csbtphonenumber;
	}

	public function setCsbtphonenumber($csbtphonenumber) {
		$this->csbtphonenumber = $csbtphonenumber;
	}

	public function getCsbtpostalcode() {
		return $this->csbtpostalcode;
	}

	public function setCsbtpostalcode($csbtpostalcode) {
		$this->csbtpostalcode = $csbtpostalcode;
	}

	public function getCsbtstate() {
		return $this->csbtstate;
	}

	public function setCsbtstate($csbtstate) {
		$this->csbtstate = $csbtstate;
	}

	public function getCsbtstreet1() {
		return $this->csbtstreet1;
	}

	public function setCsbtstreet1($csbtstreet1) {
		$this->csbtstreet1 = $csbtstreet1;
	}

	public function getCsbtstreet2() {
		return $this->csbtstreet2;
	}

	public function setCsbtstreet2($csbtstreet2) {
		$this->csbtstreet2 = $csbtstreet2;
	}

	public function getCsptcurrency() {
		return $this->csptcurrency;
	}

	public function setCsptcurrency($csptcurrency) {
		$this->csptcurrency = $csptcurrency;
	}

	public function getCsptgrandtotalamount() {
		return $this->csptgrandtotalamount;
	}

	public function setCsptgrandtotalamount($csptgrandtotalamount) {
		$this->csptgrandtotalamount = $csptgrandtotalamount;
	}

	public function getCsmdd6() {
		return $this->csmdd6;
	}

	public function setCsmdd6($csmdd6) {
		$this->csmdd6 = $csmdd6;
	}

	public function getCsmdd7() {
		return $this->csmdd7;
	}

	public function setCsmdd7($csmdd7) {
		$this->csmdd7 = $csmdd7;
	}

	public function getCsmdd8() {
		return $this->csmdd8;
	}

	public function setCsmdd8($csmdd8) {
		$this->csmdd8 = $csmdd8;
	}

	public function getCsmdd9() {
		return $this->csmdd9;
	}

	public function setCsmdd9($csmdd9) {
		$this->csmdd9 = $csmdd9;
	}

	public function getCsmdd10() {
		return $this->csmdd10;
	}

	public function setCsmdd10($csmdd10) {
		$this->csmdd10 = $csmdd10;
	}

	public function getCsmdd11() {
		return $this->csmdd11;
	}

	public function setCsmdd11($csmdd11) {
		$this->csmdd11 = $csmdd11;
	}	
	
	public function getData() {
		$output  = parent::getXmlData();
		$output .= $this->products_data->getData("product");
		return $output;
	}			
}