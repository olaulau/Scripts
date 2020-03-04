mkdir adminer && cd adminer/
composer require dg/adminer-custom:dev-master
vim index.php
	<?php
	touch(__DIR__ . '/adminer.css');
	require __DIR__ . '/../../vendor/dg/adminer-custom/index.php'; // CHECK THAT PATH IS CORRECT

-> http://localhost/adminer/
