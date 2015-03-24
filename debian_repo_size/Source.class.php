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
	
	private function get_arch_string() {
		if($this->deb === 'deb-src') {
			return 'source';
		}
		else {
			$tab = explode('-', $this->deb);
			if(isset($tab[1]))
				$arch = $tab[1];
			else
				$arch = 'i386';
			return 'binary-' . $arch;
		}
	}
	
 	public function get_packages_file_url() {
 		return $this->uri . '/dists/' . $this->dist . '/' . $this->component . '/' . $this->get_arch_string() . '/' . 'Packages.bz2'; 
 	}
	
}

// $line = 'deb http://archive.ubuntu.com/ubuntu/ utopic main restricted universe multiverse';
// $sources = Source::get_sources_from_line($line);

// print_r($sources);
// die;

// foreach ($sources as $src) {
// 	echo $src->get_packages_file_url() . PHP_EOL;
// }

?>