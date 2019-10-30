#!/bin/bash

##  check root
sudo -v
if [ $? -ne 0 ]
then
	echo "this script needs sudo rights to run."
	exit 1
fi

echo -n "new mysql admin password ? "
read -s password
echo

sql="
DROP USER IF EXISTS admin@localhost;
CREATE USER 'admin'@'localhost' IDENTIFIED BY '$password';
GRANT ALL PRIVILEGES ON *.* TO 'admin'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;"
file=`tempfile`
echo "$sql" > $file

sudo mysql -u root < $file
rm $file
