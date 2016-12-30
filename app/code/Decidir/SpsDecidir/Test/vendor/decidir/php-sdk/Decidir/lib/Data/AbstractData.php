<?php
namespace Decidir\Data;

abstract class AbstractData {
	
	private $field_required = array();
	private $field_optional = array();
	
	public function __construct(array $data) {
		foreach($this->field_required as $field => $options) {
			if(isset($options["original"])) {
				if(!array_key_exists($options["original"],$data))
					throw new \Decidir\Exception\RequiredValue($options["name"]);
				$this->$field = $data[$options["original"]];
				unset($data[$options["original"]]);				
			} else {
				if(!array_key_exists($field,$data))
					throw new \Decidir\Exception\RequiredValue($options["name"]);
				$this->$field = $data[$field];
				unset($data[$field]);
			}
		}
		
		foreach($this->field_optional as $field => $options) {
			if(isset($options["original"])) {
				if(array_key_exists($options["original"],$data)) {
					$this->$field 	= $data[$options["original"]];
					unset($data[$options["original"]]);
				}			
			} else {
				if(array_key_exists($field,$data)) {
					$this->$field 	= $data[$field];
					unset($data[$field]);
				}
			}
		}
		
		if(count($data) > 0)
			throw new \Decidir\Exception\DecidirException("Campos adicionales a los esperados: ". implode(", ",array_keys($data)), 99977, $this);
	}
	
	protected function getXmlData() {
		$output = "";
		foreach(array_merge($this->field_required, $this->field_optional) as $field => $options) {
			if(isset($options["xml"]))
				if($this->$field != null)
					$output .= '<'.$options["xml"].'>'.$this->$field.'</'.$options["xml"].'>';
		}

		return $output;
	}
	
	protected function getRequiredFields() {
		return $this->field_required;
	}
	
	protected function getOptionalFields() {
		return $this->field_optional;
	}

	protected function setRequiredFields($data) {
		$this->field_required = array_merge($data, $this->field_required);
	}
	
	protected function setOptionalFields($data) {
		$this->field_optional = array_merge($data, $this->field_optional);
	}
}
