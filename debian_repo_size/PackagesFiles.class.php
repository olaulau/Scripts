<?php

require_once './Exception_Error_handling.php';
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
				$big_array[$cpt][] = $line;
			}
		}
		unset($file);
		// droping last empty element
		if(empty($big_array[$cpt][0]))
			unset($big_array[$cpt]);
// 		print_r($big_array); die;

		$big_array2 = array();
		foreach ($big_array as $big_cpt => &$small_array) {
// 			echo "NEW PACKAGE" . PHP_EOL;
			$big_array2[] = array();
			foreach ($small_array as $small_cpt => &$line) {
// 				echo "NUM $big_cpt : "; print_r($big_array2[$big_cpt]);
				if($line !== '') {
// 					echo 'LINE : "' . $line . '"' . PHP_EOL;
					try {
						list($attribute, $value) = explode(': ', $line, 2);
					}
					catch (Exception $ex) {
						echo "Exception thrown : " . $ex . PHP_EOL;
						echo "line : $line" . PHP_EOL;
						die;
					}
						
					if(!isset($big_array2[$big_cpt][$attribute]))
						$big_array2[$big_cpt][$attribute] = $value;
					else
						echo "BUG : attribut dupliqué : $attribute = " . $big_array2[$big_cpt][$attribute] . ' <= ' . $value . PHP_EOL;
					unset($small_array[$small_cpt]);
				}
			}
		}
		unset($big_array);
// 		print_r($big_array2); die;
		return $big_array2;
	}
	
	
	public static function print_attributes_stats_from_array($array)
	{
		$db = new PDO('sqlite::memory:');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$db->exec("
			CREATE TABLE IF NOT EXISTS attributes (
			id INTEGER PRIMARY KEY,
			attribute TEXT
		)");
		
		$insert = "
			INSERT INTO attributes (attribute)
			VALUES (:attribute)
		";
		$stmt = $db->prepare($insert);
		$stmt->bindParam(':attribute', $attribute);
		
		foreach ($array as $big_cpt => $small_array) {
			foreach ($small_array as $attribute => $value) {
				$stmt->execute();
			}
		}
		
		$result = $db->query("
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
		$db = new PDO('sqlite::memory:');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$db->exec("
			CREATE TABLE IF NOT EXISTS packages (
			id INTEGER PRIMARY KEY,
			Architecture TEXT,
			Bugs TEXT,
			Description TEXT,
			Description_md5 TEXT,
			Filename TEXT,
			MD5sum TEXT,
			Maintainer TEXT,
			Origin TEXT,
			Package TEXT,
			Priority TEXT,
			SHA1 TEXT,
			SHA256 TEXT,
			Section TEXT,
			Size TEXT,
			Supported TEXT,
			Version TEXT,
			Installed_Size TEXT
		)");
	
		$insert = "
			INSERT INTO packages (Architecture, Bugs, Description, Description_md5, Filename, MD5sum, Maintainer, Origin, Package, Priority, SHA1, SHA256, Section, Size, Supported, Version, Installed_Size)
			VALUES (:Architecture, :Bugs, :Description, :Description_md5, :Filename, :MD5sum, :Maintainer, :Origin, :Package, :Priority, :SHA1, :SHA256, :Section, :Size, :Supported, :Version, :Installed_Size)
		";
		$stmt = $db->prepare($insert);
		
		$Package = new Package();
		$stmt->bindParam(':Architecture', $Package->Architecture);
		$stmt->bindParam(':Bugs', $Package->Bugs);
		$stmt->bindParam(':Description', $Package->Description);
		$stmt->bindParam(':Description_md5', $Package->Description_md5);
		$stmt->bindParam(':Filename', $Package->Filename);
		$stmt->bindParam(':MD5sum', $Package->MD5sum);
		$stmt->bindParam(':Maintainer', $Package->Maintainer);
		$stmt->bindParam(':Origin', $Package->Origin);
		$stmt->bindParam(':Package', $Package->Package);
		$stmt->bindParam(':Priority', $Package->Priority);
		$stmt->bindParam(':SHA1', $Package->SHA1);
		$stmt->bindParam(':SHA256', $Package->SHA256);
		$stmt->bindParam(':Section', $Package->Section);
		$stmt->bindParam(':Size', $Package->Size);
		$stmt->bindParam(':Supported', $Package->Supported);
		$stmt->bindParam(':Version', $Package->Version);
		$stmt->bindParam(':Installed_Size', $Package->Installed_Size);
				
		foreach ($array as $big_cpt => $small_array) {
			$Package->from_array($small_array);
			$stmt->execute();
		}
		return $db;
	}
	
	
	public static function print_packages_stats_from_db($db)
	{
		$attribute = 'Maintainer';
		$result = $db->query("
			SELECT COUNT ($attribute) AS nb, $attribute as attribute
			FROM packages
			GROUP BY $attribute
			ORDER BY nb DESC
		");
		$result->setFetchMode(PDO::FETCH_ASSOC);
				
		foreach($result as $row) {
			echo $row['nb'] . ' x ' . $row['attribute'] . PHP_EOL;
		}
	}
	
}

?>