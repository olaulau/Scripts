	ServerName localhost
	ServerAlias 127.0.0.1
	ServerAlias ::1
	DocumentRoot /var/www/html
	<Directory /var/www/html>
	    Options -Indexes +FollowSymLinks +MultiViews
	    AllowOverride All
	    Require all granted
	</Directory>
