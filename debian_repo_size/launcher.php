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
		Package::create_table();
		Source::create_table();
	}
	
}



//////////////////////////////////////

echo "DB INIT ... " . PHP_EOL;
launcher::prepare_db();
launcher::create_tables();

echo "DEB ... " . PHP_EOL;
$debline = 'deb http://archive.ubuntu.com/ubuntu/ utopic main restricted universe multiverse';
$sources = Source::get_sources_from_line($debline);

echo "DOWNLOAD AND UNCOMPRESS ... " . PHP_EOL;
$src = $sources[0];
$src->download_packages_file();
$src->uncompress_packages_file();

echo "DATA INTEGRATION ... " . PHP_EOL;
$big_array = PackagesFiles::load_file_into_array(Source::$packages_filename);
// print_r($big_array); die;
PackagesFiles::load_array_into_db($big_array);
unset($big_array);

echo "PRINT STATS ... " . PHP_EOL;
PackagesFiles::print_packages_stats();

