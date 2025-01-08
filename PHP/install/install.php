#!/usr/bin/php
<?php
// version compat check
$php_version = phpversion();
if(version_compare($php_version, "8.0", "<")) {
	require_once(__DIR__ . "/php7_compat.php");
}


// check root
exec ( "sudo -v" , $output , $return_var );
if ( $return_var !== 0 ) {
	die("this script needs sudo rights to run." . PHP_EOL);
}


// params handling
$script_name = realpath($argv[0]);
unset($argv[0]); // remove useless script name
$params = [];
foreach($argv as $arg) {
	if(str_starts_with($arg, "--")) {
		$arg = substr($arg, 2);
		if(str_contains($arg, "=")) {
			list($key, $value) = explode("=", $arg);
			$params [$key] = $value;
		}
		else {
			$params [$arg] = true;
		}
	}
}


// format params as env vars to pass them kindly to subscripts
$env = "";
foreach($params as $key => $value) {
	$env .= "$key=$value ";
}


// to have same PHP executable & version for sub scripts
$php_executable = "/usr/bin/php";
$interpreter = realpath($_SERVER["_"] ?? "");
if(!empty($interpreter) && $interpreter != $script_name) {
	$php_executable = $interpreter;
}


// launch sub-scripts
passthru( "sudo -E $env $php_executable ./install_1.php " /*. implode(' ', $params)*/ );

if (!empty($params["user"])) {
	passthru( "cd ../../HTTPD/mkcert/ && ./mkcert.sh " . $params["user"] );
	passthru( "cd ../../HTTPD/mkcert/ && ./ssl_vhost.sh localhost localhost+2" );
	passthru( "sudo -E $php_executable ./install_2.php " . $params["user"] );
}
else {
	die("you didn't specify any user, so no fpm / vhost are created." . PHP_EOL);
}

passthru( "sudo systemctl restart apache2" );
