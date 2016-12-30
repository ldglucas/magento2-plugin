<?php
namespace Decidir\Data\Cybersource;

class Collection implements \ArrayAccess , \IteratorAggregate
{
    private $container = array();

    public function __construct() {
		
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
	
    public function getIterator() {
        return new \ArrayIterator($this->container);
    }
	
	public function getArrayData($type) {
		$data = array();
		foreach($this->container as $item) {
			if($type == "product") {
				$data['csitproductcode'][] 			= $item->getCsitproductcode();
				$data['csitproductdescription'][] 	= $item->getCsitproductdescription();
				$data['csitproductname'][] 			= $item->getCsitproductname();
				$data['csitproductsku'][] 			= $item->getCsitproductsku();
				$data['csittotalamount'][] 			= $item->getCsittotalamount();
				$data['csitquantity'][] 			= $item->getCsitquantity();
				$data['csitunitprice'][] 			= $item->getCsitunitprice();
			} else {
				$data['csitpassengeremail'][] 		= $item->getCsitpassengeremail();
				$data['csitpassengerfirstname'][] 	= $item->getCsitpassengerfirstname();
				$data['csitpassengerid'][] 			= $item->getCsitpassengerid();
				$data['csitpassengerlastname'][] 	= $item->getCsitpassengerlastname();
				$data['csitpassengerphone'][] 		= $item->getCsitpassengerphone();
				$data['csitpassengerstatus'][] 		= $item->getCsitpassengerstatus();
				$data['csitpassengertype'][] 		= $item->getCsitpassengertype();				
			}
		}
		return $data;
	}
	
	public function getData($type) {
		$arrData = $this->getArrayData($type);
		$output = "";
		foreach($arrData as $key => $value) {
			$output .= '<'.strtoupper($key).'>'.join("#",$value).'</'.strtoupper($key).'>';
		}
		return $output;
	}
}