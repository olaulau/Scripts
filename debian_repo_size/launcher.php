#! /usr/bin/php
<?php

require_once './Exception_Error_handling.php';
require_once './PackagesFiles.class.php';
require_once './Source.class.php';


class launcher {
	
	private static $db;
	
	public function get_db() {
		return launcher::$db;
	}
	
	public static function prepare_db() {
		if(!isset(launcher::$db)) {
			launcher::$db = new PDO('sqlite::memory:');
			launcher::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	}
	
	
	public function create_tables() {
		
	}
	
}



//////////////////////////////////////
launcher::prepare_db();

$big_array = PackagesFiles::load_file_into_array(Source::$packages_filename);
// print_r($big_array); die;


// PackagesFiles::print_attributes_stats_from_array($big_array);
/*
résultat :

8320 x Architecture
8320 x Bugs
8320 x Description
8320 x Description-md5
8320 x Filename
8320 x MD5sum
8320 x Maintainer
8320 x Origin
8320 x Package
8320 x Priority
8320 x SHA1
8320 x SHA256
8320 x Section
8320 x Size
8320 x Supported
8320 x Version
8301 x Installed-Size
*/


PackagesFiles::load_array_into_db($big_array);
PackagesFiles::print_packages_stats();



?>