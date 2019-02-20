<a href="phpinfo.php">PhpInfo</a> <br/>
<a href="/phpmyadmin/">PhpMyAdmin</a> <br/>
<a href="/adminer/">adminer</a> <br/>
<br/>

<?php
// constants
$home_path = '/home';
$www_dir = 'public_html';

// get users
$cmd = "getent passwd | sort";
$users = explode(PHP_EOL, trim(shell_exec($cmd)));
foreach($users as &$user) {
	$user = explode(':', $user);
}
unset($user);
//var_dump($users); die;

// get FPMs
$cmd = "apt list php*.*-fpm --installed | grep php | cut -d'/' -f1";
$fpms = explode(PHP_EOL, trim(shell_exec($cmd)));
foreach($fpms as &$fpm) {
	if (preg_match('/php((\d)\.(\d))/', $fpm, $matches)) {
		$matches['short'] = 'php'.$matches[2].$matches[3];
		$fpm = $matches;
	}
}
unset($fpm);
//var_dump($fpms); die;

// print users & fpms
foreach($users as $user) {
	$user_www_dir = $home_path . '/' . $user[0] . '/' . $www_dir;
	if(file_exists($user_www_dir)) {
		echo '<a href="~' . $user[0] . '">' . $user[0] . '</a> ';
		foreach($fpms as $fpm) {
			echo '<a href="http://' . $fpm['short'] . '.' . $user[0] . '.localhost' . '">' . $fpm[0] . '</a> ';
		}
	}
}

