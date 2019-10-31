	ServerName php[MAJOR][MINOR].[USER].localhost
	#ServerAlias [USER].localhost
	DocumentRoot /var/www/html
	<Directory /var/www/html>
	    Options -Indexes +FollowSymLinks +MultiViews
	    AllowOverride All
	    Require all granted
	</Directory>
	<FilesMatch \.php$>
	    SetHandler "proxy:unix:/run/php/php[MAJOR].[MINOR]-[USER]-fpm.sock|fcgi://localhost/"
	</FilesMatch>
