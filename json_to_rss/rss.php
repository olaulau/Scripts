<?php

error_reporting(-1);

require_once('functions.inc.php');
require_once('config.inc.php');


// get JSON feed
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $conf['json_url']);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
$res = curl_exec($ch);
curl_close($ch);

// decode JSON into PHP array
$res = json_decode($res, TRUE);
// print_r($res);


rebuild_rss($res['results']);

exit;
