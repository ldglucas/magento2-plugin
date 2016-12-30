<?php
namespace Decidir\Data\Cybersource;

class Product extends \Decidir\Data\AbstractData {
	protected $csitproductcode;
	protected $csitproductdescription;
	protected $csitproductname;
	protected $csitproductsku;
	protected $csittotalamount;
	protected $csitquantity;
	protected $csitunitprice;

	public function __construct($productData) {
		$this->setRequiredFields(array(	
			"csitproductcode" => array(
				"name" => "CS - Product - CSITPRODUCTCODE", 
				"xml" => "CSITPRODUCTCODE"
			),
			"csitproductdescription" => array(
				"name" => "CS - Product - CSITPRODUCTDESCRIPTION", 
				"xml" => "CSITPRODUCTDESCRIPTION"
			),
			"csitproductname" => array(
				"name" => "CS - Product - CSITPRODUCTNAME", 
				"xml" => "CSITPRODUCTNAME"
			),
			"csitproductsku" => array(
				"name" => "CS - Product - CSITPRODUCTSKU", 
				"xml" => "CSITPRODUCTSKU"
			),	
			"csittotalamount" => array(
				"name" => "CS - Product - CSITTOTALAMOUNT", 
				"xml" => "CSITTOTALAMOUNT"
			),
			"csitquantity" => array(
				"name" => "CS - Product - CSITQUANTITY", 
				"xml" => "CSITQUANTITY"
			),
			"csitunitprice" => array(
				"name" => "CS - Product - CSITUNITPRICE", 
				"xml" => "CSITUNITPRICE"
			),		
		));		
		
		parent::__construct($productData);
		
		$this->csittotalamount = number_format($this->csittotalamount,2,".","");
		$this->csitunitprice = number_format($this->csitunitprice,2,".","");
		$this->csitquantity = number_format($this->csitquantity,0,"","");		
	}
	
	public function getCsitproductcode() {
		return $this->csitproductcode;
	}

	public function setCsitproductcode($csitproductcode) {
		$this->csitproductcode = $csitproductcode;
	}

	public function getCsitproductdescription() {
		return $this->csitproductdescription;
	}

	public function setCsitproductdescription($csitproductdescription) {
		$this->csitproductdescription = $csitproductdescription;
	}

	public function getCsitproductname() {
		return $this->csitproductname;
	}

	public function setCsitproductname($csitproductname) {
		$this->csitproductname = $csitproductname;
	}

	public function getCsitproductsku() {
		return $this->csitproductsku;
	}

	public function setCsitproductsku($csitproductsku) {
		$this->csitproductsku = $csitproductsku;
	}

	public function getCsittotalamount() {
		return $this->csittotalamount;
	}

	public function setCsittotalamount($csittotalamount) {
		$this->csittotalamount = $csittotalamount;
	}

	public function getCsitquantity() {
		return $this->csitquantity;
	}

	public function setCsitquantity($csitquantity) {
		$this->csitquantity = $csitquantity;
	}

	public function getCsitunitprice() {
		return $this->csitunitprice;
	}

	public function setCsitunitprice($csitunitprice) {
		$this->csitunitprice = $csitunitprice;
	}
}