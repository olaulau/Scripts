#!/bin/php
<?php
// USAGE
// ./gitea_clone_repos.php

// DESCRIPTION
// clone every repo of the user in archived, forks, and sources directories


// $base_url = "";
// $token = "";
if(empty($base_url) || empty($token)) {
	echo "gitea base url ? ";
	$base_url = trim(fgets(STDIN));
	
	echo "gitea user token ? ";
	$token = trim(fgets(STDIN));
}


$api_url = "$base_url/api/v1";
$method_url = "user/repos";
$url = "$api_url/$method_url?token=$token";
$headers = "accept: application/json";


$ch = curl_init($url);
if ($ch === false) {
	die("unknown curl error");
}
curl_setopt($ch, CURLOPT_HEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch);
// var_dump($content); die;
if($content === false) {
	die("curl error : " . curl_errno($ch) . " : " . curl_error($ch));
}
$infos = curl_getinfo($ch);
curl_close($ch);
// var_dump($infos); die;
$repos = json_decode($content);
// var_dump($repos); die;

if ($infos['http_code'] === 403) {
	die("token seems invalid : " . $repos->message);
}


mkdir(getcwd() . "/gitea_forks");
mkdir(getcwd() . "/gitea_archived");
mkdir(getcwd() . "/gitea_sources");

foreach ($repos as $repo) {
	if ($repo->fork) { // forks
		$dir = getcwd() . "/gitea_forks";
	}
	elseif ($repo->archived) { // archived
		$dir = getcwd() . "/gitea_archived";
	}
	else { // sources
		$dir = getcwd() . "/gitea_sources";
	}
	
	$path = "$dir/$repo->name";
	if (file_exists($path)) { // skip already existing paths
		echo "$repo->name already exists in $dir";
		continue;
	}
	
	echo "$repo->name" . PHP_EOL;
	$cmd = "git clone $repo->ssh_url $path";
	exec($cmd, $output, $return_var);
	echo "$return_var" . PHP_EOL;
	foreach ($output as $row) {
		echo $row . PHP_EOL;
	}
	
	echo PHP_EOL;
}
