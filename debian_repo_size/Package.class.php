<?php

class Package {
	
	public $Architecture = null;
	public $Filename = null;
	public $Origin = null;
	public $Package = null;
	public $Section = null;
	public $Size = null;
	
	public static $attribute_list = array('Architecture', 'Filename', 'Origin', 'Package', 'Section', 'Size');
	
	
	public function from_array($array) {
		foreach (Package::$attribute_list as $attribute) {
			Package::affect_value_from_array($this, $array, $attribute);
		}
	}
	
	
	private static function affect_value_from_array(&$object, $array, $attribute) {
		$sanitized_attribute = str_replace('-', '_', $attribute);
		if(isset($array[$attribute])) {
			$object->$sanitized_attribute = $array[$attribute];
		}
		else {
			unset($object->$sanitized_attribute);
		}
	}
	
}

?>