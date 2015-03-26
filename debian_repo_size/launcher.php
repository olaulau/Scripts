#! /usr/bin/php
<?php

require_once './Exception_Error_handling.php';
require_once './PackagesFile.class.php';
require_once './Source.class.php';
require_once 'SourcesList.class.php';


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

echo "DEB's DOWNLOAD, UNCOMPRESS, INTEGRATION ... " . PHP_EOL;
$sl = SourcesList::read_file('mirror.list');
foreach ($sl->list as $src) {
	echo $src->get_source_line() . PHP_EOL;
	$src->insert_into_db();
	$src->download_packages_file();
	$src->uncompress_packages_file();

	$pf = new PackagesFile($src);
	$pf->load_file_into_array(Source::$packages_filename);
	$pf->load_array_into_db();
	unset($pf);
}

echo "PRINT STATS ... " . PHP_EOL;
PackagesFile::print_packages_stats();

