#! /bin/bash

NB_CPU=8
governor="ondemand" ## conservative / ondemand / performance

let i=0
while [ $i -lt $NB_CPU ]
do
	echo $governor > /sys/devices/system/cpu/cpu$i/cpufreq/scaling_governor
	let i=$i+1
done


##todo
# list scaling mode
# get cpu number

# CS script to launch it with high priority, performance mode, and return to ondemand when exiting

