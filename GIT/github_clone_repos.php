#!/bin/php
<?php
// USAGE
// ./github_clone_repos.php <USER>

// DESCRIPTION
// clone every public repo of <USER> in current path


if (count($argv) !== 2) {
	die("parameter problem".PHP_EOL);
}
$user = $argv[1];

$url = "https://api.github.com/users/$user/repos";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch);
curl_close($ch);

// $tmp_filename = __DIR__ . '/' . 'repos.json';
// file_put_contents($tmp_filename, $content);
// $content = file_get_contents($tmp_filename);
// unlink ($tmp_filename);

$repos = json_decode($content);
// var_dump($repos); die;


mkdir(getcwd() . "/github_${user}_sources");
mkdir(getcwd() . "/github_${user}_archived");
mkdir(getcwd() . "/github_${user}_forks");

foreach ($repos as $repo) {
	if ($repo->fork) { // forks
		$dir = "github_${user}_forks";
	}
	elseif ($repo->archived) { // archived
		$dir = "github_${user}_archived";
	}
	else { // sources
		$dir = "github_${user}_sources";
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
