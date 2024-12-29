# pre-install
```
mkdir ~/public_html

sudo -i
adduser www-data <user>
vim /etc/apache2/mods-enabled/php8.3.conf
	comment disabling php for userdir
systemctl restart apache2.service
cd /var/www/html 
```

# adminer
```
mkdir adminer && cd adminer/
composer require dg/adminer-custom:dev-master
touch adminer.css
vim index.php
	<?php
	require __DIR__ . '/vendor/dg/adminer-custom/index.php';
chown -R www-data:www-data .
cd ..
```
--> http://localhost/adminer/

# userdirs
```
cd /var/www/html
mv index.html index.BAK
exit
sudo cp <project>/HTTPD/userdirs/{index.php,phpinfo.php} /var/www/html
```
--> http://localhost/
