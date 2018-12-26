# Dyn DNS for OVH API

## Requirements
- PHP 5
- GIT
- curl & wget
- composer (installation instructions below)


## How to install

**download**  

GIT way | wget way
--- | ---
git clone https://github.com/olaulau/Scripts <br/> cd Scripts/dyn_dns/ovh | mkdir -p dyn_dns/ovh && cd dyn_dns/ovh  <br/> wget https://github.com/olaulau/Scripts/raw/master/dyn_dns/ovh/dyn_dns_ovh.php <br/> wget https://github.com/olaulau/Scripts/raw/master/dyn_dns/ovh/dyn_dns_ovh.config.php <br/> wget https://github.com/olaulau/Scripts/raw/master/dyn_dns/ovh/composer.json <br/> chmod u+x dyn_dns_ovh.php
&nbsp;  

**install composer (only if needed) **  
`curl -sS https://getcomposer.org/installer | php`  

 | admin way | not admin way
 --- | --- | ---
**then** | sudo mv composer.phar   /usr/local/bin/composer |
**for everyone** | composer install | php composer.phar install
&nbsp;  

**configure**  
```
cp dyn_dns_ovh.config.EXAMPLE.php dyn_dns_ovh.config.php  
vim dyn_dns_ovh.config.php  
```  
fill-in config :  
$applicationKey , $applicationSecret , $consumer_key  
$zone , $subdomain  
&nbsp;  

**first run**  
`./dyn_dns_ovh.php`  

You can  run it in a crontab every minute to be sure any IP change will be propagated quickly to OVH DNS :
`vim /etc/cron.d/dyn_dns_ovh`
```
SHELL=/bin/sh
PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin

# m h dom mon dow user  command
*       *       *       *       *       root    ( cd /root/Scripts/dyn_dns/ovh/ && ./dyn_dns_ovh.php >> dyn_dns_ovh.log )
```
