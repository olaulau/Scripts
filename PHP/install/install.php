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


// check root
exec ( "sudo -v" , $output , $return_var );
if ( $return_var !== 0 ) {
	die("this script needs sudo rights to run." . PHP_EOL);
}


// launch sub-script as root
unset($argv[0]);
passthru( "sudo ./install_.php " . implode(' ', $argv) );

