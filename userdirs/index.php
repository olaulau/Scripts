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
//var_dump($users); die;

foreach($users as $user) {
	$user_www_dir = $home_path . '/' . $user[0] . '/' . $www_dir;
	if(file_exists($user_www_dir)) {
		echo '<a href="~' . $user[0] . '">' . $user[0] . '</a>';
	}
}

