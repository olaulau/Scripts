#! /usr/bin/php
<?php

require_once './PackagesFiles.class.php';


$test_file = 'Packages';

$big_array = PackagesFiles::load_file_into_array($test_file);
// print_r($big_array); die;


// PackagesFiles::print_attributes_stats_from_array($big_array);
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
*/


$db = PackagesFiles::load_array_into_db($big_array);
PackagesFiles::print_packages_stats_from_db($db);



?>