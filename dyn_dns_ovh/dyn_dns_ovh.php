#! /usr/bin/php
<?php

require_once 'dyn_dns_ovh.config.php';


// retrive real external IP address
$ip_url = "http://myexternalip.com/raw";
$command = "wget --inet4-only --quiet -O - $ip_url | tr -d '\n'";
$external_ip = `$command`;
// echo $external_ip;
$external_ip = '86.196.101.209';


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
// 	echo "<<" . $record['target'] . ">> \n";
// 	echo "<<" . $external_ip . ">> \n";
// 	exit;
	
	// if zone's target is not actual ip address
	if($record['target'] != $external_ip) {
		// update the OVH domain record zone
		$content = array('target' => $external_ip, 'subDomain' => $subdomain, 'ttl' => 0);
		$res = $ovh->put('/domain/zone/' . $zone . '/record/' . $record_id, $content);
		// refresh the zone
		$content = null;
		$res = $ovh->post('/domain/zone/' . $zone . '/refresh', $content);
		echo date('r') . " : DNS updated from " . $record['target'] . " to $external_ip \n";
	}
	else {
		echo date('r') . " : no DNS change (keeping " . $record['target'] . ") \n";
	}	
}
