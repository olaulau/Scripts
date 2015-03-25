<?php

require_once 'launcher.php';


class Source {
	
	private $deb;
	private $uri;
	private $dist;
	private $component;
	
	public static $packages_compresed_filename = 'Packages.bz2';
	public static $packages_filename = 'Packages';
	public static $default_arch = 'i386';
	
	
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
				$arch = Source::$default_arch;
			return 'binary-' . $arch;
		}
	}
	
	
 	public function get_packages_file_url() {
 		return $this->uri . '/dists/' . $this->dist . '/' . $this->component . '/' . $this->get_arch_string() . '/' . Source::$packages_compresed_filename; 
 	}
	
 	
 	public function download_packages_file() {
 		$url = $this->get_packages_file_url();
 		$ch = curl_init();
 		curl_setopt($ch, CURLOPT_URL,$url);
 		
 		$fp = fopen(Source::$packages_compresed_filename, 'w');
 		curl_setopt($ch, CURLOPT_FILE, $fp);
 		
 		curl_setopt($ch, CURLOPT_HEADER, 0);
 		curl_exec($ch);
 		curl_close($ch);
 	}
 	
 	
 	public function uncompress_packages_file() {
		$sfp = bzopen(Source::$packages_compresed_filename, "r");
 		$fp = fopen(Source::$packages_filename, "w");
 		while (!feof($sfp)) {
 			$string = bzread($sfp, 4096);
 			fwrite($fp, $string, strlen($string));
 		}
 		bzclose($sfp);
 		fclose($fp);
 	}
}


$line = 'deb http://archive.ubuntu.com/ubuntu/ utopic main restricted universe multiverse';
$sources = Source::get_sources_from_line($line);

// print_r($sources);
// die;

// foreach ($sources as $src) {
// 	echo $src->get_packages_file_url() . PHP_EOL;
// }

// $sources[0]->download_packages_file();
// $sources[0]->uncompress_packages_file();


?>