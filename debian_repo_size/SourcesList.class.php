<?php

require_once './Source.class.php';

class SourcesList {
	
	private $list = array();
	
	public static function read_file($filename) {
		$sl = new SourcesList();
		$file = new SplFileObject($filename);
		while (!$file->eof()) {
			$line = trim($file->fgets());
			if(strpos($line, '#') !== 0  &&  $line !== '') { // line not commented with a '#' at start
// 				echo "processing line : $line" . PHP_EOL;
				$sl->list = array_merge($sl->list, Source::get_sources_from_line($line));
			}
		}
		return $sl;
	}
	
}

// $filename = 'sources.list';
// $sl = SourcesList::read_file($filename);
// print_r($sl);
// die;
