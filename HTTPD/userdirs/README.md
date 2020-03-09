```
mkdir adminer && cd adminer/
composer require dg/adminer-custom:dev-master
vim index.php
	<?php
	touch(__DIR__ . '/adminer.css');
	require __DIR__ . '/vendor/dg/adminer-custom/index.php';
chown -R www-data:www-data .
```  
  
-> http://localhost/adminer/
