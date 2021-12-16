#!/usr/bin/php
<?php

// requirements :
// apt install -y php-cli curl

// usage : 
// ./install.php [--<MODE>] [<USER>]
// <MODE> = update (only packages)
// <USER> = unix user to create fpm & vhost

// examples :
// ./install.php $USER		        =>  install all with fpm vhosts for currrent user (dev workstation)
// ./install.php					=>  install all without fpm and vhost (shared hosting)
// ./install.php --update [$USER]   =>  only install all available packages (new php is out)


// check root
exec ( "sudo -v" , $output , $return_var );
if ( $return_var !== 0 ) {
	die("this script needs sudo rights to run." . PHP_EOL);
}


// params handling
unset($argv[0]); // script name, useless
$params = $argv;

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

// launch sub-scripts
passthru( "sudo ./install_1.php " . implode(' ', $params) );
if (!empty($user)) {
	passthru( "cd ../../HTTPD/mkcert/ && ./mkcert.sh $user" );
	passthru( "cd ../../HTTPD/mkcert/ && ./ssl_vhost.sh localhost localhost+2" );
	passthru( "sudo ./install_2.php $user" );
}
passthru( "sudo systemctl restart apache2" );

