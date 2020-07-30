#!/usr/bin/php
<?php

// params handling
$user = $argv[1];	


// get PHP version list
$cmd = "find /etc/php/*/fpm -name php.ini";
$res = shell_exec ($cmd);
$inis = explode(PHP_EOL, trim($res));

$phps = [];
foreach($inis as $ini) {
	$res = preg_match('|^/etc/php/(\d)\.(\d)/fpm/php\.ini$|', $ini, $matches);
	if ($res === 1) {
		$phps[] = "$matches[1]$matches[2]";
	}
}


// configure apache ssl vhost
foreach($phps as $php) {
	passthru("cd ../../HTTPD/mkcert/ && ./ssl_vhost.sh php$php.$user.localhost _wildcard.$user.localhost");
}
passthru("systemctl restart apache2");

