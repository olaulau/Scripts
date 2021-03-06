#!/usr/bin/php
<?php
// constants
$php_regex = 'php((\d)\.(\d))';


// params handling
unset($argv[0]); // script name, useless

$update_mode = false;
$update_arg_pos = array_search('--update', $argv);
if ($update_arg_pos !== false) {
	$update_mode = true;
	unset($argv[$update_arg_pos]);
}

if(count($argv) > 1) {
	var_dump($argv);
	die("too many parameters".PHP_EOL);
	
}
elseif(count($argv) === 1) {
	$argv = array_values($argv);
	$user = $argv[0];
}


// prepare apt
if(!$update_mode) {
	$os_release = parse_ini_file('/etc/os-release');
	if ($os_release === false) {
		die("unable to read os release");
	}
	if (empty($os_release['ID'])) {
		die("unable to find os release");
	}
	
	if ($os_release['ID'] === 'debian') {
		passthru("wget -q https://packages.sury.org/php/apt.gpg -O sury.gpg");
		passthru("add-apt-key sury.gpg");
		unlink("sury.gpg");
		file_put_contents("/etc/apt/sources.list.d/sury.org.list", "deb [signed-by=/usr/share/keyrings/sury.gpg] https://packages.sury.org/php/ buster main".PHP_EOL);
		file_put_contents("/etc/apt/sources.list.d/sury.org.list", "deb [signed-by=/usr/share/keyrings/sury.gpg] https://packages.sury.org/apache2/ buster main".PHP_EOL, FILE_APPEND);
	}
	else if ($os_release['ID'] === 'ubuntu') {
		passthru("add-apt-repository --yes ppa:ondrej/php");
		passthru("add-apt-repository --yes ppa:ondrej/apache2");
	}
	else {
		die("invalid os release");
	}
	passthru("apt -qq update");
	passthru("apt -qq full-upgrade -y");
}


// get PHP version list
$cmd = "apt list 'php*' 2> /dev/null | cut -d'/' -f1 | grep -P '^$php_regex$' | sort | uniq";
$res = shell_exec ($cmd);
$php_versions = explode(PHP_EOL, trim($res));
$phps = [];
foreach($php_versions as $php) {
	$res = preg_match('/^'.$php_regex.'$/', $php, $matches);
	if ($res !== false) {
		$matches['short'] = "php{$matches[2]}{$matches[3]}";
		$phps[] = $matches;
	}
}
unset($php_versions);
//var_dump($phps); die;

//TODO pre install all php versions

//TODO set default PHP to previous last version
// update-alternatives --set php /usr/bin/php7.4

// install all php modules
$php_exclude = [
	'list', 
	'composer', 
	'php-dev', 
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
	'php-google-auth', // break php-google-api-php-client
	'letodms', // too much dependencies
	'mockery',
	'sabre',
	'sodium',
	'tideways', // warning, and paid
	'php-cboden-ratchet', // dependency fail on debian testing
	'php-nesbot-carbon', // dependency fail on debian testing
	'php-robmorgan-phinx', // dependency fail on debian testing
	'php-twig-string-extra', // dependency fail on debian testing
	'dbgsym', // debug symbols'
	'recode',
	'phpunit',
	'phalcon4', // conflict with phalcon3 (phalcon) config file
	'mapscript', // warning already loaded
	'memcached',
	'redis',
	'uopz', // make die and exit not working anymore
	'gearman', // ubuntu 16.04'
	"enchant", // debian
	"irods", //no candidates
	"async-aws",
	"phpseclib",
	"ps",
	"solr-all-dev",
	"snmp",
	"decimal",
];
// get and filter lists issued by 'apt list' commands (instead of php*) : php-* , php\d.\d-*
$cmd = "apt list 'php*' 2> /dev/null | grep php | cut -d'/' -f1 | sort | uniq 2> /dev/null";
$php_packages = explode(PHP_EOL, trim(shell_exec($cmd)));
// foreach($php_packages as $pack) {	echo $pack . PHP_EOL;	} die;

$php_packages = array_filter ($php_packages, function ($package) use ($php_exclude) {
	if (preg_match('/^php-/', $package) || preg_match('/^php\d\.\d/', $package)) {
		foreach($php_exclude as $exclude) {
			if (strpos ($package, $exclude) !== false) {
				return false;
			}
		}
		return true;
	}
	else {
		return false;
	}
});
// foreach($php_packages as $pack) {     echo $pack . PHP_EOL;   } die;

$cmd = "apt -y install " . implode(' ', $php_packages) . " 2> /dev/null";
passthru($cmd, $res);


// configure php (create common /etc/php/php.ini, backup each php.ini files)
copy ('/etc/php/'.end($phps)[1].'/fpm/php.ini', '/etc/php/php.ini');
passthru ('find /etc/php/*/ -name php.ini -exec cp {} {}.BAK \;');
foreach ($phps as $php) {
	passthru ("systemctl restart php{$php[1]}-fpm ");
}


// build global PHP service (create php.sh script, add service to systemd)
passthru("mkdir -p /root/bin");
copy('php.sh', '/root/bin/php.sh');
$row = "systemctl \$action";
foreach($phps as $php) {
	$row .= " $php[0]-fpm";
}
file_put_contents('/root/bin/php.sh', $row.PHP_EOL,  FILE_APPEND);
chmod ("/root/bin/php.sh", 0744);
copy('php.service', '/etc/systemd/system/php.service');
passthru("systemctl daemon-reload");


// test if we have to create user conf (fpm & vhost)
if(empty($user)) {
	die("you didn't specify any user, so no fpm / vhost are created.");
}


// configure fpm (create user pool, disable and restart php)
foreach($phps as $php) {
	$content = file_get_contents("/etc/php/{$php[1]}/fpm/pool.d/www.conf");
	$content = str_replace("[www]", "[$user]", $content);
	$content = str_replace("user = www-data", "user = $user", $content);
	$content = str_replace("group = www-data", "group = $user", $content);
	$content = str_replace("listen = /run/php/php{$php[1]}-fpm.sock", "listen = /run/php/php{$php[1]}-$user-fpm.sock", $content);
	file_put_contents("/etc/php/{$php[1]}/fpm/pool.d/$user.conf", $content);
}
passthru("systemctl unmask php");
passthru("systemctl enable php");
passthru("systemctl disable php");
passthru("systemctl restart php");


// configure apache vhost
foreach($phps as $php) {
	$content = file_get_contents("./vhost.conf");
	$content = str_replace("[USER]", $user, $content);
	$content = str_replace("[MAJOR]", $php[2], $content);
	$content = str_replace("[MINOR]", $php[3], $content);
	file_put_contents("/etc/apache2/sites-available/{$php['short']}.$user.localhost.conf", $content);
	$content = file_get_contents("./vhost.inc");
	$content = str_replace("[USER]", $user, $content);
	$content = str_replace("[MAJOR]", $php[2], $content);
	$content = str_replace("[MINOR]", $php[3], $content);
	file_put_contents("/etc/apache2/sites-available/{$php['short']}.$user.localhost.inc", $content);
	passthru("a2ensite {$php['short']}.$user.localhost.conf");
}
passthru("a2enmod actions rewrite userdir alias proxy_fcgi");
passthru("systemctl restart apache2");

