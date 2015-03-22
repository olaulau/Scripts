<?php

class Package {
	
	public $Architecture = null;
	public $Bugs = null;
	public $Description = null;
	public $Description_md5 = null;
	public $Filename = null;
	public $MD5sum = null;
	public $Maintainer = null;
	public $Origin = null;
	public $Package = null;
	public $Priority = null;
	public $SHA1 = null;
	public $SHA256 = null;
	public $Section = null;
	public $Size = null;
	public $Supported = null;
	public $Version = null;
	public $Installed_Size = null;
	
	
	public function from_array($array) {
		foreach ($array as $attribute => $value) {
			Package::affect_value_from_array($this, $array, $attribute);
		}
	}
	
	
	private static function affect_value_from_array(&$object, $array, $attribute) {
		if(isset($array[$attribute])) {
			$sanitized_attribute = str_replace('-', '_', $attribute);
			$object->$sanitized_attribute = $array[$attribute];
		}
		else {
			unset($object->$$sanitized_attribute);
		}
	}
	
}

?>