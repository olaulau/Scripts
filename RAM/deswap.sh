#!/bin/bash
  
devices=`blkid | grep 'TYPE="swap"' | cut -d':' -f1`

for device in $devices
do
    /sbin/swapoff $device
    /sbin/swapon $device
done
