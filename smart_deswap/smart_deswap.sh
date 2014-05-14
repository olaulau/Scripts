#! /bin/bash

let FREE_RAM=`free -m | head -n3 | tail -n1 | tr -s ' ' | cut -f4 -d' '`
let USED_SWAP=`free -m | head -n4 | tail -n1 | tr -s ' ' | cut -f3 -d' '`
let DELTA=$FREE_RAM-$USED_SWAP
#echo $DELTA

if [ "$DELTA" -gt "0" ]
then
	swapoff -a && swapon -a
else
	echo "not enough available RAM to deswap"
fi

