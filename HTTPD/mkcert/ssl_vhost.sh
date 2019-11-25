#!/bin/bash


##  check root
sudo -v
if [ $? -ne 0 ]
then
	echo "this script needs sudo rights to run."
	exit 1
fi


##  check params
if [ $# -ne 2 ]
then
	echo "parameter problem"
	exit 1
fi
servername=$1
certname=$2


##  prepare ssl include file
echo  \
"		SSLEngine on
		SSLCertificateFile	/etc/ssl/mkcert/$certname.pem
		SSLCertificateKeyFile /etc/ssl/mkcert/$certname-key.pem" \
| sudo tee /etc/apache2/sites-available/$servername-ssl.inc > /dev/null


##  ssl vhost
echo \
"<VirtualHost *:443>
	Include /etc/apache2/sites-available/$servername.inc
	
	Include /etc/apache2/sites-available/$servername-ssl.inc
</VirtualHost>" \
| sudo tee /etc/apache2/sites-available/$servername-ssl.conf > /dev/null


##  enable vhost
sudo a2ensite $servername-ssl.conf

