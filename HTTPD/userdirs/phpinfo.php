<?php

$processUser = posix_getpwuid(posix_geteuid());
echo "script executed by : " . $processUser['name'];

phpinfo();
