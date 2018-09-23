#!/bin/bash

source migration_site.config.sh


## cleanup
if [ $DO_CLEANUP == true ]
then
	echo "cleanup ..."
	rm -Rf tmp
fi


## preparing
mkdir -p tmp
cd tmp


## getting source
if [ $DO_IMPORT == true ]
then
	echo "getting soutce ..."
	echo "$SRC_SHELL_PASSWORD" > sshpass.txt
	
	echo "getting source database ..."
	sshpass -f sshpass.txt ssh $SRC_SHELL_USER@$SRC_SHELL_HOST "export MYSQL_PWD=$SRC_DB_PASSWORD && mysqldump $SRC_DB_NAME -u $SRC_DB_USER | lbzip2"  | lbunzip2 > database.sql
	
	echo "getting source website ..."
	mkdir -p website
	sshpass -f sshpass.txt ssh $SRC_SHELL_USER@$SRC_SHELL_HOST "tar cf - -C $SRC_SHELL_DIRECTORY ./ | lbzip2" | lbunzip2 | tar x -C website
	
	rm sshpass.txt
fi


## applying modifications to website
if [ $DO_MODIFICACTIONS == true ]
then
	echo "applying modifications to website ..."
	
fi



## cleanup destination (tables and files) ??



## pushing destination
if [ $DO_EXPORT == true ]
then
	echo "pushing destination ..."
	echo "$DEST_SHELL_PASSWORD" > sshpass.txt
	
	echo "pushing destination database ..."
	sshpass -f sshpass.txt ssh $DEST_SHELL_USER@$DEST_SHELL_HOST "printf \"[client]\npassword=$DEST_DB_PASSWORD\n\" > mysqlpass.txt"
	cat database.sql | lbzip2 | sshpass -f sshpass.txt ssh $DEST_SHELL_USER@$DEST_SHELL_HOST "lbunzip2 | mysql --defaults-extra-file=mysqlpass.txt -u $DEST_DB_USER $DEST_DB_NAME"
	sshpass -f sshpass.txt ssh $DEST_SHELL_USER@$DEST_SHELL_HOST "rm mysqlpass.txt"
	
	echo "pushing destination website ..."
	tar cf - -C website ./ | lbzip2 | sshpass -f sshpass.txt ssh $DEST_SHELL_USER@$DEST_SHELL_HOST "lbunzip2 | tar x -C $DEST_SHELL_DIRECTORY"
	
	rm sshpass.txt
fi


## cleanup
if [ $DO_CLEANUP == true ]
then
	echo "cleanup ..."
	rm -Rf tmp
fi
