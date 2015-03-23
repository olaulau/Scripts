<?php

class Source {
	
	private $deb;
	private $uri;
	private $dist;
	private $component;
	
	public static function get_sources_from_line($line) {
		$res = explode(' ', $line, 4);
		$components = explode(' ', $res[3]);
		$sources = array();
		foreach ($components as $component) {
			$src = new Source();
			$src->deb = $res[0];
			$src->uri = $res[1];
			$src->dist = $res[2];
			$src->component = $component;
			$sources[] = $src;
		}
		return $sources;
	}
	
// 	public 
	
}

// $line = 'deb http://archive.ubuntu.com/ubuntu/ utopic main restricted universe multiverse';
// $sources = Source::get_sources_from_line($line);
// print_r($sources);
// die;

?>