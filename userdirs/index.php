<a href="phpinfo.php">PhpInfo</a> <br/>
<a href="/phpmyadmin/">PhpMyAdmin</a> <br/>
<a href="/adminer/">adminer</a> <br/>
<br/>

<?php

$home_path = '/home';
$www_dir = 'public_html';

$home_dir = dir($home_path);
while( ($user=$home_dir->read()) !== FALSE ) {
	if($user != '.' && $user != '..') {
		$user_www_dir = $home_path . '/' . $user . '/' . $www_dir;
		if(file_exists($user_www_dir)) {
			echo '<a href="~' . $user . '">' . $user . '</a>';
		}
	}
}
