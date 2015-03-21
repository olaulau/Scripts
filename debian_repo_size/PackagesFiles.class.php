<?php

require_once './Exception_Error_handling.php';


class PackagesFiles
{
	
	public static function load_file_into_array($filename)
	{
		$big_array = array();
		
		$big_array[] = array();
		$cpt = 0;
		$file = new SplFileObject($filename);
		while (!$file->eof()) {
			$line = $file->fgets();
			if($line === PHP_EOL) {
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
		// print_r($big_array);
		// die;

		$big_array2 = array();
		foreach ($big_array as $big_cpt => &$small_array) {
			$big_array2[] = array();
			foreach ($small_array as $small_cpt => &$line) {
				if($line !== '') {
					try {
						list($attribute, $value) = explode(':', $line, 2);
					}
					catch (Exception $ex) {
						echo "Exception thrown : " .PHP_EOL;
						echo $ex .PHP_EOL;
						echo "line : $line" .PHP_EOL;
						die;
					}
						
					if(!isset($big_array2[$big_cpt][$attribute]))
						$big_array2[$big_cpt][$attribute] = $value;
					else
						echo "attribut duppliqué : $attribute";
					unset($small_array[$small_cpt]);
				}
		
			}
			;
		}
		unset($big_array);
		// print_r($big_array2);
		// die;
		return $big_array2;
	}
	
	
	public static function print_atributes_stats_from_array($array)
	{
		$memory_db = new PDO('sqlite::memory:');
		$memory_db->setAttribute(PDO::ATTR_ERRMODE,	PDO::ERRMODE_EXCEPTION);
		
		$memory_db->exec("
				CREATE TABLE IF NOT EXISTS attributes (
				id INTEGER PRIMARY KEY,
				attribute TEXT)
				");
		
		$insert = "
				INSERT INTO attributes (attribute)
				VALUES (:attribute)";
		$stmt = $memory_db->prepare($insert);
		$stmt->bindParam(':attribute', $attribute);
		
		foreach ($array as $big_cpt => $small_array) {
			foreach ($small_array as $attribute => $value) {
				try {
					$stmt->execute();
				}
				catch(Exception $ex) {
					;
				}
			}
		}
		
		$result = $memory_db->query("
				SELECT count(*) AS nb, attribute
				FROM attributes
				GROUP BY attribute
				HAVING nb > 8000
				ORDER BY nb DESC");
		foreach($result as $row) {
			echo $row['nb'] . " x " . $row['attribute'] . "\n";
		}
	}
	
}

?>