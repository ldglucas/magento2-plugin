<?php
namespace Decidir\Data\Cybersource;

class DigitalGoods extends AbstractData
{
	protected $csmdd32;
	
	protected $products_data = null;

	public function __construct($digitalgoodsData, $productsData) {
		$this->products_data = new Collection();
		
		$this->setRequiredFields(array(	
			"csmdd32" => array(
				"name" => "CS - DigitalGoods - CSMDD32", 
				"xml" => "CSMDD32"
			),
		));
		
		parent::__construct($digitalgoodsData);	
		
		foreach($productsData as $product) {
			$this->products_data[] = new Product($product);
		}
	}
	
	public function getCsmdd32(){
		return $this->csmdd32;
	}

	public function setCsmdd32($csmdd32){
		$this->csmdd32 = $csmdd32;
	}
			
}