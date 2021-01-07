#!/bin/bash

DIR="/var/archives"		# archive storage directory
GREP="etc"				# archives file filter
FIND="etc/group"		# archive content file to find

for file in `ls $DIR | grep $GREP | grep ".tar.bz2"`
do
	output=`tar -jtf  $DIR/$file | grep $FIND`
	if [ $? -eq 0 ]	# grep returned some rows
	then # found
		echo [ $file ]
		echo "$output"
	fi
done

