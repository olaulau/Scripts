<?php

// PHP packages whitelist
$php_include = [
	"amqp",
	"apcu",
	"bcmath",
	"bz2",
	"cgi",
	"cli",
	"curl",
	"date",
	"db",
	"fpdf",
	"fpm",
	"gd",
	"getid3",
	"gmp",
	"igbinary",
	"image-text",
	"imagick",
	"imap",
	"intl",
	"json",
	"ldap",
	"log",
	"mail",
	"mbstring",
	"mcrypt",
	"memcache",
	"memcached",
	"mock",
	"mockery",
	"monolog",
	"mysql",
	"net-url",
	"oauth",
	"odbc",
	"parser",
	"pear",
	"pgsql",
	"redis",
	"soap",
	"snmp",
	"sqlite3",
	"ssh2",
	"tcpdf",
	//"tidy", // Dépend: libtidy5deb1 (>= 1:5.2.0) mais il n'est pas installable
	"tokenizer",
	"uuid",
	"validate",
	"xdebug",
	"xml",
	"yaml",
	"zip",
	"zmq",
];


// PHP packages blacklist
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
