#!/bin/bash

##  check root
sudo -v
if [ $? -ne 0 ]
then
	echo "this script needs sudo rights to run."
	exit 1
fi

snaps=`snap list | tail -n +2 | cut -d' ' -f1`
for snap in $snaps
do
	#echo "SNAP = $snap"
	apts=`apt list $snap 2>/dev/null | grep '/' | cut -d'/' -f1 | uniq`
	for apt in $apts
	do
		#echo "APT = $apt"
		if [ $snap == $apt ]
		then
			echo "converting $apt from SNAP to APT ..."
			sudo apt install -y $apt
			sudo snap remove $snap
			break
		#else
			#echo "pas pareil"
		fi
	done
done

