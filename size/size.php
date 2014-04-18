#! /usr/bin/php
<?php

## config vars
$MIN_SIZE = 1000;
$MIN_PART = 0.02;
$DEBUG = FALSE;
	//$DEBUG = TRUE;



## command-line params treatment
$RUN_CMD = $argv[0];
if(!empty($argv[1]))
	$DIR = $argv[1];
else
	$DIR = getcwd();
$DIR = realpath($DIR);
if($DEBUG)	echo "current dir : '$DIR'" . PHP_EOL;

if(empty($argv[2]))
	$DEPTH = 0;
else
	$DEPTH = $argv[2];
if($DEBUG)	echo "current depth : '$DEPTH'" . PHP_EOL;





## compute
$command = "du --all --max-depth=1 -m --apparent-size --no-dereference --one-file-system --total \"$DIR\" \
 | sort --numeric-sort --reverse ";
if($DEBUG)	echo "commande : << $command >>" . PHP_EOL;
exec($command, $array, $return);

list($total) = explode("\t", $array[0]);
if($DEPTH == 0)
	echo "$total $DIR" . PHP_EOL;
//unset($array[0]);	//gruge : zap total

foreach($array as $line) {
	if($DEBUG)	echo "line : $line" . PHP_EOL;
	list($size, $file) = explode("\t", $line);
	if($DEBUG)	echo "currenf file : '$file'" . PHP_EOL;
	if($file !== $DIR  &&  $file !== 'total') {
		if(
			//1 ||  // gruge : zap vérif tailles
			$size >= $MIN_SIZE  &&  $size >= $total*$MIN_PART) {
			echo str_pad($size, 10) . "\t" . $file . PHP_EOL;
			if(is_dir($file)) {
				if($DEBUG)	echo "test : " . escapeshellcmd($file) . PHP_EOL;
				$command = $RUN_CMD . ' "' . ($file) . '" ' . ($DEPTH+1);
				if($DEBUG)	echo "launching : $command" . PHP_EOL;
				passthru($command);	//gruge : pas de récurcivité
			}
			else {
				if($DEBUG)	 echo "pas dir" . PHP_EOL;
			}
		}
	}
	else {
		if($DEBUG)	 echo "zap current file/dir" . PHP_EOL;
	}
	
}


