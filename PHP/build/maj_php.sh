#!/bin/bash

## 
## this script updates manually compiled PHP build from sources
## 


## config
major_version='7.0'
minor_version='2'
let last_version_minor=$minor_version-1
# last_version='30' ## force last version number, if you do not compile every release
nb_threads=6


## compute things and verify
major_version_short=`echo $major_version | tr -d '.'`
version="${major_version}.${minor_version}"
last_version="${major_version}.${last_version_minor}"
echo "MAJOR VERSION SHORT = $major_version_short"
echo "NEW VERSION = $version"
echo "LAST VERSION = $last_version"


## build commands
cd /usr/local
rm php-${last_version}.tar.bz2
wget --content-disposition http://fr2.php.net/get/php-${version}.tar.bz2/from/this/mirror
rm -Rf buildphp${major_version_short}
mkdir buildphp${major_version_short}
cd buildphp${major_version_short}
tar --strip-components=1 -jxvf ../php-${version}.tar.bz2


if [ $major_version = "7.0" ]
then
	echo "PHP 7"
	./configure \
	--prefix=/usr/local/php${major_version_short} \
	--enable-bcmath \
	--enable-calendar \
	--enable-cgi \
	--enable-cli \
	--enable-exif \
	--enable-ftp \
	--enable-inline-optimization \
	--enable-intl \
	--enable-mbregex \
	--enable-mbstring \
	--enable-pcntl \
	--enable-phar \
	--enable-posix \
	--enable-soap \
	--enable-sockets \
	--enable-sysvmsg \
	--enable-sysvsem \
	--enable-sysvshm \
	--enable-wddx \
	--enable-zip \
	--with-bz2=/usr \
	--with-cdb \
	--with-curl=/usr/bin \
	--with-freetype-dir=/usr \
	--with-gd \
	--with-gettext \
	--with-iconv-dir=/usr \
	--with-icu-dir=/usr \
	--with-jpeg-dir=/usr \
	--with-mcrypt=/usr \
	--with-mhash \
	--with-mysql-sock=/var/run/mysqld/mysqld.sock \
	--with-mysqli=mysqlnd \
	--with-openssl \
	--with-openssl-dir=/usr/bin \
	--with-pdo-mysql=mysqlnd \
	--with-pdo-pgsql \
	--with-pdo-sqlite=/usr \
	--with-pear \
	--with-pgsql \
	--with-png-dir=shared,/usr \
	--with-pspell \
	--with-readline \
	--enable-shmop \
	--with-sqlite3=/usr \
	--with-tidy=/usr \
	--with-xmlrpc \
	--with-xpm-dir=/usr \
	--with-xsl=/usr \
	--with-zlib-dir=/usr
	echo $?
else
	echo "PHP 5"
	./configure \
	--prefix=/usr/local/php${major_version_short} \
	--enable-bcmath \
	--enable-calendar \
	--enable-cgi \
	--enable-cli \
	--enable-exif \
	--enable-ftp \
	--enable-inline-optimization \
	--enable-intl \
	--enable-mbregex \
	--enable-mbstring \
	--enable-pcntl \
	--enable-phar \
	--enable-posix \
	--enable-soap \
	--enable-sockets \
	--enable-sysvmsg \
	--enable-sysvsem \
	--enable-sysvshm \
	--enable-wddx \
	--enable-zip \
	--with-bz2=/usr \
	--with-cdb \
	--with-curl=/usr/bin \
	--with-freetype-dir=/usr \
	--with-gd \
	--with-gettext \
	--with-iconv-dir=/usr \
	--with-icu-dir=/usr \
	--with-jpeg-dir=/usr \
	--with-mcrypt=/usr \
	--with-mhash \
	--with-mysql-sock=/var/run/mysqld/mysqld.sock \
	--with-mysql=mysqlnd \
	--with-mysqli=mysqlnd \
	--with-openssl \
	--with-openssl-dir=/usr/bin \
	--with-pcre-regex=/usr \
	--with-pdo-mysql=mysqlnd \
	--with-pdo-pgsql \
	--with-pdo-sqlite=/usr \
	--with-pear \
	--with-pgsql \
	--with-png-dir=shared,/usr \
	--with-pspell \
	--with-readline \
	--with-regex=php \
	--enable-shmop \
	--with-sqlite3=/usr \
	--with-tidy=/usr \
	--with-xmlrpc \
	--with-xpm-dir=/usr \
	--with-xsl=/usr \
	--with-zlib-dir=/usr
	echo $?
fi

make -j ${nb_threads}
echo $?

make install
echo $?

