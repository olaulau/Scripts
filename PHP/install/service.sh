#!/bin/bash

if [ $1 == "start" ] || [ $1 == "stop" ] || [ $1 == "reload" ] || [ $1 == "restart" ] || [ $1 == "enable" ] || [ $1 == "disable" ]
then
	action=$1
else
	echo "invalid action"
	exit 1
fi

