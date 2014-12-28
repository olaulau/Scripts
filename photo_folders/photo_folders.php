#!/usr/bin/php
<?php

$cwd = getcwd();
// echo $cwd;
$list = scandir($cwd);
// print_r($list);

$tab = array();
foreach ($list as $file) {
	if(is_file($file)) {
// 		echo "$file\n";
		$regexp = '/^[[:alpha:]]{3}_([[:digit:]]{8})_[[:digit:]]{6}([_~][[:digit:]]{1,})?\.[[:alnum:]]{3}/';
		$matches = array();
		$res = preg_match($regexp, $file, $matches);
		if($res === 1) {
			if(isset($matches[1])) {
				$date = $matches[1];
// 				echo $date;
				if(!isset($tab[$date])) {
					$tab[$date] = array();
				}
				$tab[$date][] = $file;
			}
		}
	}
}
// print_r($tab);
// exit;

foreach ($tab as $date => $files) {
	$formated_date = substr($date , 0, 4) . '-' . substr($date , 4, 2) . '-' . substr($date , 6, 2) . '_' ;
	if(!file_exists($formated_date)) {
		mkdir($formated_date);
	}
	foreach ($files as $file) {
		rename($file, $formated_date.DIRECTORY_SEPARATOR.$file);
	}
}
echo "done ! \n";
echo count($list) . " files moved in " . count($tab) . " directories.\n";

?>