#! /bin/bash

sudo -v

SCRIPT=`realpath $0`
SCRIPTPATH=`dirname $SCRIPT`
cd $SCRIPTPATH

echo "préparation lancement CS GO :"
sudo ../CPU/governors.sh performance
steam steam://rungameid/730 &
sleep 30
sudo ../CPU/renicer.sh


## later ...
# sudo ../CPU/governors.sh ondemand

exit

