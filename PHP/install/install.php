#!/usr/bin/php
<?php
// check root
exec ( "sudo -v" , $output , $return_var );
if ( $return_var !== 0 ) {
	die("this script needs sudo rights to run." . PHP_EOL);
}


// params handling
unset($argv[0]); // remove useless script name
$params = [];
foreach($argv as $arg)
{
	if(str_starts_with($arg, "--"))
	{
		$arg = substr($arg, 2);
		if(str_contains($arg, "="))
		{
			list($key, $value) = explode("=", $arg);
			$params [$key] = $value;
		}
		else
		{
			$params [$arg] = true;
		}
	}
}
// var_dump($params); die;


// format params as env vars to pass them kindly to subscripts
$env = "";
foreach($params as $key => $value) {
	$env .= "$key=$value ";
}
// var_dump($env); die;


// launch sub-scripts
passthru( "sudo $env ./install_1.php " . implode(' ', $params) );
if (!empty($params["user"])) {
	passthru( "cd ../../HTTPD/mkcert/ && ./mkcert.sh " . $params["user"] );
	passthru( "cd ../../HTTPD/mkcert/ && ./ssl_vhost.sh localhost localhost+2" );
	passthru( "sudo ./install_2.php " . $params["user"] );
}
passthru( "sudo systemctl restart apache2" );
