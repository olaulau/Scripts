<?php

// php package exclusion list
$php_exclude = [
	'list', 
	'composer', 
	'dev', 
	'phpdocumentor', 
	'psr', 
	'zend', 
	'symfony', 
	'doctrine', 
	'horde', 
	'react', 
	'illuminate', 
	'zeta', 
	'apcu', 
	'gmagick', 
	'letodms-lucene', 
	'yac',
	'libsodium',
	'raphf', // segfault on PHP 5.6
	'http', // depend on raphf
	'mailparse', // error on CLI
	'cassandra', // error on PHP 7.3 CLI
	'lua', // error on PHP 7.3 CLI
	'mysqlnd-ms', // error on PHP 5.6 CLI
	'google-auth', // break php-google-api-php-client
	'letodms', // too much dependencies
	'mockery',
	'sabre',
	'sodium',
	'tideways', // warning, and paid
	'cboden-ratchet', // dependency fail on debian testing
	'nesbot-carbon', // dependency fail on debian testing
	'robmorgan-phinx', // dependency fail on debian testing
	'twig-string-extra', // dependency fail on debian testing
	'dbgsym', // debug symbols
	"phpdbg",
	'recode',
	'phpunit',
	'phalcon4', // conflict with phalcon3 (phalcon) config file
	'mapscript', // warning already loaded
	'memcached',
	'redis',
	'uopz', // make die and exit not working anymore
	'gearman', // ubuntu 16.04
	"enchant", // debian
	"irods", // no candidates
	"async-aws",
	"phpseclib",
	"ps",
	"solr-all-dev",
	"snmp",
	"decimal",
	"laravel",
	"swoole",
	"elisp", // elpa mode
	"apigen",
	"libvirt", // unable to load module
	"solr", // throws deprecated with PHP 8.1 
	"mapi", // overrides include_path with kopano
	"guestfs", // warning on PHP 8.1
	"adldap2", // dependency pb with lavavel & symfony
	"protobuf", // deprecated php 8.1
	"geos", // warning php 7.4
 	"laravel", "illuminate", "symfony", "dragonmantank",
 	"ratchet", "grpc", "stomp",
	"psr", "phalcon5", "memcache", "mongodb", "oauth", "xdebug", "yaml", "propro", "inotify", "rrd", "ssh2", "uploadprogress", "xhprof", "excimer", "zeroc-ice", 
];
