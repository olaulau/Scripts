<?php

require_once 'launcher.php';
require_once './Package.class.php';


class PackagesFiles
{
	
	public static function load_file_into_array($filename)
	{
		$big_array = array();
		
		$big_array[] = array();
		$cpt = 0;
		$file = new SplFileObject($filename);
		while (!$file->eof()) {
			$line = trim($file->fgets());
// 			echo 'LINE : "' . $line . '"'; die;
			if($line === '') {
				$big_array[] = array();
				$cpt ++;
			}
			else {
				list($attribute, $value) = explode(': ', $line, 2);
				$big_array[$cpt][$attribute] = $value;
			}
		}
		unset($file);
		// droping last empty element
		if(empty($big_array[$cpt][0]))
			unset($big_array[$cpt]);
// 		print_r($big_array); die;
		return $big_array;
	}
	
	
	public static function print_attributes_stats_from_array($array)
	{
		launcher::get_db()->exec("
			CREATE TABLE IF NOT EXISTS attributes (
			id INTEGER PRIMARY KEY,
			attribute TEXT
		)");
		
		$insert = "
			INSERT INTO attributes (attribute)
			VALUES (:attribute)
		";
		$stmt = launcher::get_db()->prepare($insert);
		$stmt->bindParam(':attribute', $attribute);
		
		foreach ($array as $big_cpt => $small_array) {
			foreach ($small_array as $attribute => $value) {
				$stmt->execute();
			}
		}
		
		$result = launcher::get_db()->query("
			SELECT count(*) AS nb, attribute
			FROM attributes
			GROUP BY attribute
			HAVING nb > 8000
			ORDER BY nb DESC");
		foreach($result as $row) {
			echo $row['nb'] . " x " . $row['attribute'] . "\n";
		}
	}
	
	
	public static function load_array_into_db($array)
	{
		$create_sql = "CREATE TABLE IF NOT EXISTS packages ( id INTEGER PRIMARY KEY, ";
		$sql_attributes = array();
		foreach (Package::$attribute_list as $attribute) {
			$sql_attributes[] = $attribute . " TEXT";
		}
		$create_sql .= implode(", ", $sql_attributes);
		$create_sql .= ")";
// 		echo $create_sql; die;
		launcher::get_db()->exec($create_sql);
		
		$insert_sql = "INSERT INTO packages ( " . implode(", ", Package::$attribute_list) . " ) ";
		$insert_sql .= " VALUES ( ";
		$sql_attributes = array();
		foreach (Package::$attribute_list as $attribute) {
			$sql_attributes[] = ":" . $attribute;
		}
		$insert_sql .= implode(", ", $sql_attributes);
		$insert_sql .= ")";
// 		echo $insert_sql; die;
		$stmt = launcher::get_db()->prepare($insert_sql);
		
		$Package = new Package();
		foreach (Package::$attribute_list as $attribute) {
			$stmt->bindParam(':'.$attribute, $Package->$attribute);
		}
				
		foreach ($array as $big_cpt => $small_array) {
			$Package->from_array($small_array);
			$stmt->execute();
		}
	}
	
	
	public static function print_packages_stats()
	{
		$result = launcher::get_db()->query("
			SELECT CAST(Size AS INT)/1024/1024 AS size, Package
			FROM packages
			WHERE size > 1000000
			ORDER BY size DESC
		");
		$result->setFetchMode(PDO::FETCH_ASSOC);
				
		foreach($result as $row) {
			echo $row['size'] . ' Mio ' . $row['Package'] . PHP_EOL;
		}
	}
	
}

?>