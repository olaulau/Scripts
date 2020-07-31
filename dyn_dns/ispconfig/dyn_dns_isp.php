#! /usr/bin/php
<?php

require_once 'dyn_dns_isp.config.php';


function vd ($var) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}
function vdd ($var) {
    vd ($var);
    die;
}


function restCall ($method, $data) {
    global $conf;
    
    if(!is_array($data)) return false;
    $json = json_encode($data);
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
    
    // needed for self-signed cert
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    // end of needed for self-signed cert
    
    curl_setopt($curl, CURLOPT_URL, $conf['ispconfig']['rest']['url'] . '?' . $method);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
    $result = curl_exec($curl);
    curl_close($curl);
    
    return $result;
}


function IspGetDnsRecord ($zone, $subdomain, $external_ip) {
    global $conf;
    
    // login
    $result = restCall('login', array(
        'username' => $conf['ispconfig']['rest']['user'],
        'password' => $conf['ispconfig']['rest']['password'],
        'client_login' => false)
    );
//     vdd($result);
    if($result) {
        $data = json_decode($result, true);
//        vdd($data);
        if(!$data)
        	die("problem with authentification");
        if ($data["code"] !== "ok" || empty($data["response"])) {
       		if(!empty($data["message"])) {
        		die( $data["message"] );
        	}
        	else {
		    	die("bad authentification");
        	}
        }
        $session_id = $data['response'];
        
        // get dns zone
        $result = restCall('dns_zone_get', array(
            'session_id' => $session_id,
            'primary_id' => ['origin' => $zone.'.']
        ));
        if(!$result)
        	die("error");
//		vdd(json_decode($result, true));
        $dns_zone = json_decode($result, true)['response'];
        
        // get dns record
        $result = restCall('dns_a_get', array(
            'session_id' => $session_id,
            'primary_id' => [
                'zone' => $dns_zone[0]['id'],
                'name' => $subdomain
            ]
        ));
        if(!$result)
        	die("error");
//		vdd(json_decode($result, true));
        $dns_record = json_decode($result, true)['response'][0];
//	vdd($dns_record);
        
        if ($external_ip !== $dns_record['data']) {
            // update dns record
            $old_ip = $dns_record['data'];
            $dns_record['data'] = $external_ip;
            $result = restCall('dns_a_update', array(
                'session_id' => $session_id,
                'client_id' => 1,
                'primary_id' => $dns_record['id'],
                'params' => $dns_record
            ));
            if(!$result)
            	die("error");
//             vd(json_decode($result, true));	die;
            $dns_update = json_decode($result, true)['response'];
            echo date('r') . " : DNS updated from " . $old_ip . " to $external_ip \n";
        }
        else {
            $dns_update = 0;
            echo date('r') . " : no DNS change (keeping " . $dns_record['data'] . ") \n";
        }
        
        // logout
        $result = restCall('logout', array('session_id' => $session_id));
        if(!$result)
        	print "Could not get logout result\n";
        
        return $dns_update;
    }
}


// retrive real external IP address
$ip_url = "http://myexternalip.com/raw";
$command = "wget --inet4-only --quiet -O - $ip_url | tr -d '\n'";
$external_ip = `$command`;
// echo $external_ip;


// update if needed
$tab = IspGetDnsRecord ($zone, $subdomain, $external_ip);
// vd($tab);


exit;