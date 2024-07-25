#!/bin/php
<?php

// debian subdir
$cmd = "rsync -arvh --delete --delete-excluded --exclude '*-32bit.iso' pub.linuxmint.com::pub/debian/ ./pub.linuxmint.com/debian/";
passthru($cmd, $result_code);


// stable subdir
$cmd = "rsync --list-only pub.linuxmint.com::pub/stable/ | awk '{print \$NF}' | grep -v '^.$'";
exec($cmd, $output, $result_code);
$all_versions = $output;

$versions_by_major = [];
foreach($all_versions as $version) {
	$versions_by_major [floor($version)] [] = $version;
}

$versions_to_keep = [];
foreach($versions_by_major as $major => $versions) {
	$versions_to_keep [] = max($versions);
}
$versions_to_exclude = array_diff($all_versions, $versions_to_keep);

$cmd = "rsync -arvh --delete --delete-excluded --exclude '*-32bit.iso' ";
foreach($versions_to_exclude as $versions_to_exclude) {
	$cmd .=  "--exclude '$versions_to_exclude/' ";
}
$cmd .= " pub.linuxmint.com::pub/stable/ ./pub.linuxmint.com/stable/";
passthru($cmd, $result_code);


// testing subdir
$cmd = "rsync -arvh --delete pub.linuxmint.com::pub/testing/ ./pub.linuxmint.com/testing/";
passthru($cmd, $result_code);

