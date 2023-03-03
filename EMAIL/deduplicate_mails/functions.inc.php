<?php

function simplify_filename($filename)
{
	return substr($filename, 0, strpos($filename, ":"));
}


function find_file($path, $prefix)
{
	$files = scandir($path);
	foreach($files as $file)
	{
		if(strpos($file, $prefix) === 0)
		{
			return $file;
		}
	}
	return $prefix;
}


function identical_files($file_one, $file_two)
{
	if(filemtime($file_one) !== filemtime($file_two))
	{
		return false;
	}
	
	if(filesize($file_one) !== filesize($file_two))
	{
		return false;
	}
	
	exec("diff $file_one $file_two", $output, $res);
	if($res !== 0)
	{
		return false;
	}
		
	return true;
}
