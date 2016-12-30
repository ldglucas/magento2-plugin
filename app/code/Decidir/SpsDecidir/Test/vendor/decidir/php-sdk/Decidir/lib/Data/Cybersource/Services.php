<?php
namespace Decidir\Data\Cybersource;

class Services extends AbstractData
{
	protected $csmdd28;
	protected $csmdd29 = null;
	protected $csmdd30 = null;
	protected $csmdd31 = null;
	
	protected $products_data = null;

	public function __construct($serviceData, $productsData) {	
		$this->products_data = new Collection();
		
		$this->setRequiredFields(array(	
			"csmdd28" => array(
				"name" => "CS - Services - CSMDD28", 
				"xml" => "CSMDD28"
			),
		));
		
		$this->setOptionalFields(array(	
			"csmdd29" => array(
				"name" => "CS - Services - CSMDD29", 
				"xml" => "CSMDD29"
			),
			"csmdd30" => array(
				"name" => "CS - Services - CSMDD30", 
				"xml" => "CSMDD30"
			),
			"csmdd31" => array(
				"name" => "CS - Services - CSMDD31", 
				"xml" => "CSMDD31"
			),			
		));
				
		parent::__construct($serviceData);	
		
		foreach($productsData as $product) {
			$this->products_data[] = new Product($product);
		}
	}
	
	public function getCsmdd28(){
		return $this->csmdd28;
	}

	public function setCsmdd28($csmdd28){
		$this->csmdd28 = $csmdd28;
	}

	public function getCsmdd29(){
		return $this->csmdd29;
	}

	public function setCsmdd29($csmdd29){
		$this->csmdd29 = $csmdd29;
	}

	public function getCsmdd30(){
		return $this->csmdd30;
	}

	public function setCsmdd30($csmdd30){
		$this->csmdd30 = $csmdd30;
	}

	public function getCsmdd31(){
		return $this->csmdd31;
	}

	public function setCsmdd31($csmdd31){
		$this->csmdd31 = $csmdd31;
	}
		
}