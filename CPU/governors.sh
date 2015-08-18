#! /bin/bash


NB_CPU=8
##aybe make an array ?
#governor="ondemand" ## conservative / ondemand / performance


## checking for root
if [[ $EUID -ne 0 ]]
then
   echo "This script must be run as root." 1>&2
   echo "Maybe try with ' sudo ' ?" 1>&2
   exit 1
fi


## checking parameter
if [[ $1 == "" ]]
then
	# default governor
	echo "default governor : ondemand"
	governor="ondemand"
else
	## check the governor parameter
	if [[ $1 == "conservative" ]] || [[ $1 == "ondemand" ]] || [[ $1 == "performance" ]]
	then
		echo "applying governor : $1"
		governor=$1
	else
		echo "governor parameter unknown"
		exit 1
	fi
fi


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

