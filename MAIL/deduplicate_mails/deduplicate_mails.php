#!/usr/bin/php
<?php
require_once __DIR__ . "/functions.inc.php";

$logfile = "/var/log/syslog.1";
$content = file_get_contents($logfile);

//$content = "";
$regex = "|^(\w* \d* \d\d:\d\d:\d\d) (.*): imap\((.*@.*)\)(.*): Warning: Fixed a duplicate: (/.*) -> (.*)$|m";
$nb = preg_match_all(
	$regex,
	$content,
	$matches
);
//var_dump($nb, $matches); die;


for($i=0; $i<$nb; $i++)
{
	$src_file = $matches[5][$i];
	$dest_file = $matches[6][$i];

	$path = dirname($src_file);
	$src_file = simplify_filename(basename($src_file));
	//$dest_file = simplify_filename($dest_file);

	$src_file = find_file($path, $src_file);
	$dest_file = find_file($path, $dest_file);
	
	if(file_exists("$path/$src_file") && file_exists("$path/$dest_file"))
	{
		if(is_file("$path/$src_file") && is_file("$path/$dest_file"))
		{
			if(identical_files("$path/$src_file", "$path/$dest_file"))
			{
				$res = unlink("$path/$dest_file");
				if($res === true)
				{
					echo "duplicate mail file deleted : $path/$dest_file" . PHP_EOL;
				}
				else
				{
					echo "error in file deletion : $path/$dest_file" . PHP_EOL;
				}
			}
			else
			{
				echo "files are not identical" . PHP_EOL;
			}
		}
		else
		{
			echo "not a regular file" . PHP_EOL;
			echo "$path/$src_file" . PHP_EOL;
			echo "$path/$dest_file" . PHP_EOL;
		}
	}
	else
	{
		echo "file does not exist" . PHP_EOL;
	}
}
