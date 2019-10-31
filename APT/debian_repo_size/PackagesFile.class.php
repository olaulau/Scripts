<?php

require_once 'launcher.php';
require_once './Package.class.php';


class PackagesFile
{
	private $src;
	
	private $big_array;
	
	
	public function __construct($src) {
		$this->src = $src;
	}
	
	public function load_file_into_array($filename)
	{
		$this->big_array = array();
		
		array_push($this->big_array, array());
		$cpt = 0;
		$file = new SplFileObject($filename);
		$previous_line = '';
		while (!$file->eof()) {
			$line = rtrim($file->fgets());
// 			echo 'LINE : "' . $line . '"' . PHP_EOL;
// 			die;
			if($line === '') {
				array_push($this->big_array, array());
				$cpt ++;
			}
			else {
				// handle multiple lines fields (additional lines start with " " or "\t")
				if ( substr_compare ($line, ' ', 0, 1) === 0  ||  substr_compare ($line, "\t", 0, 1) === 0 ) {
					$line = $previous_line . $line;
				}
				list($attribute, $value) = explode(': ', $line, 2);
				$this->big_array[$cpt][$attribute] = $value;
			}
			$previous_line = $line;
		}
		unset($file);
		// droping last empty element
		if(empty($this->big_array[$cpt][0]))
			unset($this->big_array[$cpt]);
// 		print_r($big_array); die;
	}
	
	
	public function load_array_into_db()
	{
		$insert_sql = "INSERT INTO packages ( source_id, " . implode(", ", Package::$attribute_list) . " ) ";
		$insert_sql .= " VALUES ( ";
		$sql_attributes = array(":source_id");
		foreach (Package::$attribute_list as $attribute) {
			$sql_attributes[] = ":" . $attribute;
		}
		$insert_sql .= implode(", ", $sql_attributes);
		$insert_sql .= ")";
// 		echo $insert_sql; die;
		$stmt = launcher::get_db()->prepare($insert_sql);
		
		$Package = new Package();
		$stmt->bindParam(':source_id', $this->src->get_id());
		foreach (Package::$attribute_list as $attribute) {
			$stmt->bindParam(':'.$attribute, $Package->$attribute);
		}
				
		foreach ($this->big_array as $big_cpt => $small_array) {
			$Package->from_array($small_array);
			$stmt->execute();
		}
	}
	
	
	public static function print_packages_stats()
	{
		$result = launcher::get_db()->query("
			SELECT Size, Package
			FROM packages
			WHERE Size > 100 * 1024 * 1024
			ORDER BY Size DESC
		");
		$result->setFetchMode(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			echo round($row['Size']/1024/1024) . ' Mio ' . $row['Package'] . PHP_EOL;
		}
		
		echo "----------------" . PHP_EOL;
		
		$result = launcher::get_db()->query("
			SELECT SUM(Size) AS size
			FROM packages
		");
		$result->setFetchMode(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			echo round($row['size']/1024/1024) . ' Mio ' . PHP_EOL;
		}
		
		echo "----------------" . PHP_EOL;
		
		$result = launcher::get_db()->query("
			SELECT SUM(Size) AS size, s.deb || ' ' || s.uri || ' ' || s.dist || ' ' || s.component AS src
			FROM packages p, sources s
			WHERE p.source_id = s.id
			GROUP BY s.id
			ORDER BY size DESC
		");
		$result->setFetchMode(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			echo round($row['size']/1024/1024) . ' Mio ' . "\t" . $row['src'] . PHP_EOL;
		}
		
		echo "----------------" . PHP_EOL;
		
		$result = launcher::get_db()->query("
				SELECT SUM(Size) AS size, s.deb AS src
				FROM packages p, sources s
				WHERE p.source_id = s.id
				GROUP BY s.deb
				ORDER BY size DESC
				");
		$result->setFetchMode(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			echo round($row['size']/1024/1024) . ' Mio ' . "\t" . $row['src'] . PHP_EOL;
		}
		
		echo "----------------" . PHP_EOL;
		
		$result = launcher::get_db()->query("
				SELECT SUM(Size) AS size, s.uri AS src
				FROM packages p, sources s
				WHERE p.source_id = s.id
				GROUP BY s.uri
				ORDER BY size DESC
				");
		$result->setFetchMode(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			echo round($row['size']/1024/1024) . ' Mio ' . "\t" . $row['src'] . PHP_EOL;
		}
		
		echo "----------------" . PHP_EOL;
		
		$result = launcher::get_db()->query("
				SELECT SUM(Size) AS size, s.dist AS src
				FROM packages p, sources s
				WHERE p.source_id = s.id
				GROUP BY s.dist
				ORDER BY size DESC
				");
		$result->setFetchMode(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			echo round($row['size']/1024/1024) . ' Mio ' . "\t" . $row['src'] . PHP_EOL;
		}
		
		echo "----------------" . PHP_EOL;
		
		$result = launcher::get_db()->query("
				SELECT SUM(Size) AS size, s.component AS src
				FROM packages p, sources s
				WHERE p.source_id = s.id
				GROUP BY s.component
				ORDER BY size DESC
				");
		$result->setFetchMode(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			echo round($row['size']/1024/1024) . ' Mio ' . "\t" . $row['src'] . PHP_EOL;
		}
	}
	
}
