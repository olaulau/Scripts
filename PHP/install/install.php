#!/usr/bin/php
<?php
// if you don't have any php yet, you have to 'apt install -y php-cli' before executing this script with : 'sudo ./install.php $USER'


// check root
$processUser = posix_getpwuid(posix_geteuid());
if ($processUser['name'] !== 'root') {
	die('you must be root, please use "sudo ./install.php $USER" !' . PHP_EOL);
}


// constants
$php_regex = 'php((\d)\.(\d))';
$user = @$argv[1];


// prepare
passthru("add-apt-repository -y -n ppa:ondrej/php");
passthru("add-apt-repository -y -n ppa:ondrej/apache2");
passthru("apt update");
passthru("apt dist-upgrade -y");


// get PHP version list
$cmd = "apt list php* 2> /dev/null | cut -d'/' -f1 | grep -P '^$php_regex$' | sort | uniq";
$res = shell_exec ($cmd);
$php_versions  = explode(PHP_EOL, trim($res));
$phps = [];
foreach($php_versions as $php) {
	$res = preg_match('/^'.$php_regex.'$/', $php, $matches);
	if ($res !== false) {
		$matches['short'] = "php{$matches[2]}{$matches[3]}";
		$phps[] = $matches;
	}
}
unset($php_versions);
/* will get you an array of elements like this :
 array(4) {
    [0]=>
    string(6) "php7.2"
    [1]=>
    string(3) "7.2"
    [2]=>
    string(1) "7"
    [3]=>
    string(1) "2"
    ['short']=>
    string(5) "php72"
  }
*/


// install all php versions
passthru("apt install -y php-all-dev 2> /dev/null");


// install all fpm
$fpms = [];
foreach($phps as $php) {
	$fpms[] = $php[0] . '-fpm';
}
$cmd = 'apt install -y ' . implode(' ', $fpms) . ' 2> /dev/null';
passthru($cmd);


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
	'mysqlnd_ms', // error on PHP 5.6 CLI
];
// get and filter lists issued by 'apt list' commands (instead of php*) : php-* , php\d.\d-*
$cmd = "apt list php* 2> /dev/null | grep php | cut -d'/' -f1 | sort | uniq 2> /dev/null";
$php_packages = explode(PHP_EOL, trim(shell_exec($cmd)));
$php_packages = array_filter ($php_packages, function ($package) use ($php_exclude) {
	if (preg_match('/^php-/', $package) || preg_match('/^php\d\.\d-/', $package)) {
	foreach($php_exclude as $exclude) {
		if (strpos ($package, $exclude) !== false) {
			return false;
		}
	}
	return true;
	}
});


$cmd = "apt install -y `apt list 'php*' 2> /dev/null | cut -d'/' -f1 | grep -v '" .implode('\|', $php_exclude)."' | sort | uniq` 2> /dev/null";
passthru($cmd);


// test if we have to create user conf
if(empty($user)) {
	die("you didn't specify any user, so no fpm/vhost are created.");
}


// configure fpm
foreach($phps as $php) {
	$content = file_get_contents("/etc/php/{$php[1]}/fpm/pool.d/www.conf");
	$content = str_replace("[www]", "[$user]", $content);
	$content = str_replace("user = www-data", "user = $user", $content);
	$content = str_replace("group = www-data", "group = $user", $content);
	$content = str_replace("listen = /run/php/php{$php[1]}-fpm.sock", "listen = /run/php/php{$php[1]}-$user-fpm.sock", $content);
	file_put_contents("/etc/php/{$php[1]}/fpm/pool.d/$user.conf", $content);
	passthru("systemctl unmask php{$php[1]}-fpm ");
	passthru("systemctl enable php{$php[1]}-fpm ");
	passthru("systemctl disable php{$php[1]}-fpm ");
	passthru("systemctl restart php{$php[1]}-fpm ");
}


// configure vhost
foreach($phps as $php) {
	$content = file_get_contents("./vhost.conf");
	$content = str_replace("[USER]", $user, $content);
	$content = str_replace("[MAJOR]", $php[2], $content);
	$content = str_replace("[MINOR]", $php[3], $content);
	file_put_contents("/etc/apache2/sites-available/{$php['short']}.$user.conf", $content);
	passthru("a2ensite {$php['short']}.$user.conf");
}
passthru("a2enmod actions fastcgi alias proxy_fcgi");
passthru("systemctl reload apache2");


// build global PHP service
passthru("mkdir -p /root/bin");
copy('service.sh', '/root/bin/php.sh');
foreach($phps as $php) {
	file_put_contents('/root/bin/php.sh', 'systemctl $action '.$php[0].'-fpm'.PHP_EOL,  FILE_APPEND);
}
passthru("systemctl daemon-reload");
