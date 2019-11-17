#!/bin/php
<?php

// ./gitea_clone_repos.php


echo "gitea base url ? ";
$base_url = trim(fgets(STDIN));

echo "gitea user token ? ";
$token = trim(fgets(STDIN));


$api_url = "$base_url/api/v1";
$method_url = "/user/repos";
$headers = "accept: application/json";
// $http_method = "GET";
$url = "$api_url$method_url?token=$token";


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
// var_dump($infos); die;
$repos = json_decode($content);

if ($infos['http_code'] === 403) {
	die("token seems invalid : " . $repos->message);
}
curl_close($ch);


foreach ($repos as $repo) {
	if ($repo->fork) {
// 		continue;
	}
	
	$path = getcwd() . '/'.$repo->name;
	if (file_exists($path)) {
		continue;
	}
	echo "$repo->name" . PHP_EOL;
	
	$cmd = "git clone $repo->ssh_url";
	exec($cmd, $output, $return_var);
	echo "$return_var" . PHP_EOL;
	foreach ($output as $row) {
		echo $row . PHP_EOL;
	}
	
	echo PHP_EOL;
}
