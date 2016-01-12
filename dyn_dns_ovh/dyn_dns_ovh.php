#! /usr/bin/php
<?php

// config
	// create API keys here : https://eu.api.ovh.com/createToken/index.cgi?GET=/*&PUT=/*&POST=/*&DELETE=/*
	// keep the rights on GET POST PUT DELETE on '/*' to be sure it'll work
$applicationKey = '';
$applicationSecret = '';
$consumer_key = '';

$zone = 'domain.fr';
$subdomain = 'subdomain';


// retrive real external IP address
$ip_url = "http://myexternalip.com/raw";
$command = "wget -q -O - $ip_url | tr -d '\n'";
$external_ip = `$command`;
// echo $external_ip;


/*
 * https://github.com/ovh/php-ovh
 * https://eu.api.ovh.com/console
 * 
 */

require __DIR__ . '/vendor/autoload.php';
use \Ovh\Api;

$endpoint_name = 'ovh-eu';
$ovh = new Api( $applicationKey,
		$applicationSecret,
		$endpoint_name,
		$consumer_key);

$content = array('fieldType' => "A", 'subDomain' => $subdomain);
$records = $ovh->get('/domain/zone/' . $zone . '/record', $content);
// print_r($records);

foreach ($records as $record_id) {
	$record = $ovh->get('/domain/zone/' . $zone . '/record/' . $record_id);
// 	print_r($record);
// 	echo "<<" . $record['target'] . ">>";
// 	echo "<<" . $external_ip . ">>";
// 	exit;
	
	// if zone's target is not actual ip address
	if($record['target'] != $external_ip) {
		// update the OVH domain record zone
		$content = array('target' => $external_ip, 'subDomain' => $subdomain);
		$res = $ovh->put('/domain/zone/' . $zone . '/record/' . $record_id, $content);
		echo date('r') . " : DNS updated from " . $record['target'] . " to $external_ip \n";
	}
	else {
		echo date('r') . " : no DNS change (keeping " . $record['target'] . ") \n";
	}
	
}
