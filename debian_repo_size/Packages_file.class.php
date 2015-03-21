#! /usr/bin/php
<?php

require_once './Exception_Error_handling.php';


$test_file = 'Packages';




$big_array = array();

$big_array[] = array();
$cpt = 0;
$file = new SplFileObject($test_file);
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











$memory_db = new PDO('sqlite::memory:');
$memory_db->setAttribute(PDO::ATTR_ERRMODE,	PDO::ERRMODE_EXCEPTION);

$memory_db->exec("
		CREATE TABLE IF NOT EXISTS attributes (
		id INTEGER PRIMARY KEY,
		attribute TEXT)
		");


$insert = "	INSERT INTO attributes (attribute)
			VALUES (:attribute)";
$stmt = $memory_db->prepare($insert);
$stmt->bindParam(':attribute', $attribute);


foreach ($big_array2 as $big_cpt => $small_array) {
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
		HAVING nb > 1000
		ORDER BY nb DESC");
foreach($result as $row) {
	echo $row['nb'] . " x " . $row['attribute'] . "\n";
}

die;



/*
résultat :

8320 x Architecture
8320 x Bugs
8320 x Description
8320 x Description-md5
8320 x Filename
8320 x MD5sum
8320 x Maintainer
8320 x Origin
8320 x Package
8320 x Priority
8320 x SHA1
8320 x SHA256
8320 x Section
8320 x Size
8320 x Supported
8320 x Version
8301 x Installed-Size
7446 x Depends
7167 x Original-Maintainer
6422 x Source
6352 x Homepage
3092 x Task
2360 x Multi-Arch
2186 x Replaces
1645 x Suggests
1510 x Pre-Depends
1316 x Conflicts
1107 x Recommends
1088 x Provides
1032 x Breaks

 */



?>