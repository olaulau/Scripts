#!/usr/bin/php
<?php

// if you don't have any php yet, you have to run 'apt install -y php-cli' before executing this script

// usage : 
// sudo ./install.php [--<MODE>] [<USER>]
// <MODE> = update (only packages)
// <USER> = unix user to create fpm & vhost

// examples :
// sudo ./install.php $USER  =>  install all with fpm vhosts for currrent user (dev workstation)
// sudo ./install.php  =>  install all without fpm and vhost (shared hosting)
// sudo ./install.php --update [$USER] =>  only install all available packages (new php is out)


// constants
$php_regex = 'php((\d)\.(\d))';
$user = @$argv[1]; ///////////////////



// params handling
unset($argv[0]); // script name, useless

$update_mode = false;
$update_arg_pos = array_search('--update', $argv);
if ($update_arg_pos !== false) {
	$update_mode = true;
	unset($argv[$update_arg_pos]);
}

if(count($argv) === 1) {
$argv = array_values($argv);
	$user = $argv[0];
}
else {
var_dump($argv);
	die("too many parameters".PHP_EOL);
}


// check root
$processUser = posix_getpwuid(posix_geteuid());
if ($processUser['name'] !== 'root') {
	die('you must be root, please use "sudo ./install.php ..." !' . PHP_EOL);
}


// prepare
if(!$update_mode) {
	passthru("add-apt-repository -y ppa:ondrej/php");
	passthru("add-apt-repository -y ppa:ondrej/apache2");
	// passthru("add-apt-repository -y ppa:ondrej/pkg-gearman");
	passthru("apt update");
	passthru("apt full-upgrade -y");
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
];
// get and filter lists issued by 'apt list' commands (instead of php*) : php-* , php\d.\d-*
$cmd = "apt list 'php*' 2> /dev/null | grep php | cut -d'/' -f1 | sort | uniq 2> /dev/null";
$php_packages = explode(PHP_EOL, trim(shell_exec($cmd)));
//var_dump($php_packages); die;
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
//var_dump($php_packages); die;

$cmd = "apt " . " -y " . " install " . implode(' ', $php_packages) . " 2> /dev/null";
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
foreach($phps as $php) {
	file_put_contents('/root/bin/php.sh', "systemctl \$action $php[0]-fpm".PHP_EOL,  FILE_APPEND);
}
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
	file_put_contents("/etc/apache2/sites-available/{$php['short']}.$user.conf", $content);
	passthru("a2ensite {$php['short']}.$user.conf");
}
passthru("a2enmod actions fcgid alias proxy_fcgi");
passthru("systemctl reload apache2");

