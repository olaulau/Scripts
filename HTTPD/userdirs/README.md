mkdir ~/public_html

sudo -i
cd /var/www/html  

# adminer
```
mkdir adminer && cd adminer/
composer require dg/adminer-custom:dev-master
vim index.php
	<?php
	touch(__DIR__ . '/adminer.css');
	require __DIR__ . '/vendor/dg/adminer-custom/index.php';
chown -R www-data:www-data .
cd ..
```
--> http://localhost/adminer/

# userdirs
```
rm index.html
cp <project>/HTTPD/userdirs/{index.php,phpinfo.php} .
```
--> http://localhost/
