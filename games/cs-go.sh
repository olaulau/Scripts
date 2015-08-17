#! /bin/bash

sudo echo "préparation lancement CS GO :"
sudo ../CPU/governors.sh performance
steam steam://rungameid/730 &
sleep 2
sudo ../CPU/renicer.sh


## later ...
# sudo ../CPU/governors.sh ondemand

exit

