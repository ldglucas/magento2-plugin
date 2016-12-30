<?php
namespace Decidir\Data\Cybersource;

class Ticketing extends AbstractData
{
	protected $csmdd33;
	protected $csmdd34;
	
	protected $products_data; // = new Collection();

	public function __construct($ticketingData, $productsData) {
		$this->setRequiredFields(array(	
			"csmdd33" => array(
				"name" => "CS - Ticketing - CSMDD33", 
				"xml" => "CSMDD33"
			),
			"csmdd34" => array(
				"name" => "CS - Ticketing - CSMDD34", 
				"xml" => "CSMDD34"
			),
		));
		
		parent::__construct($ticketingData);

		foreach($productsData as $product) {
			$this->products_data[] = new Product($product);
		}
	}
	
	public function getCsmdd33(){
		return $this->csmdd33;
	}

	public function setCsmdd33($csmdd33){
		$this->csmdd33 = $csmdd33;
	}

	public function getCsmdd34(){
		return $this->csmdd34;
	}

	public function setCsmdd34($csmdd34){
		$this->csmdd34 = $csmdd34;
	}		
	
}