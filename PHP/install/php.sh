#!/bin/bash

if [ $1 == "start" ] || [ $1 == "stop" ] || [ $1 == "reload" ] || [ $1 == "restart" ] || [ $1 == "enable" ] || [ $1 == "disable" ] || [ $1 == "mask" ] || [ $1 == "unmask" ]
then
	action=$1
else
	echo "invalid action"
	exit 1
fi

find /etc/php/*/ -name php.ini -exec cp /etc/php/php.ini {} \;

