#! /bin/bash


## getting system values
NB_CPU=`nproc`
declare -a governors=(`cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_available_governors`)


## checking for root
if [[ $EUID -ne 0 ]]
then
   echo "This script must be run as root." 1>&2
   echo "Maybe try with 'sudo' ?" 1>&2
   exit 1
fi


## checking parameter
if [[ $1 == "" ]]
then
	echo "misssiong governor parameter, pick one from '${governors[@]}'"
	echo "actual governor is `cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_governor`"
	exit 1
else
	## check the governor parameter
	if [[ " ${governors[@]} " =~ " ${1} " ]]
	then
		governor=$1
	else
		echo "governor '${1}' not supported by this system, pick one from '${governors[@]}'"
		exit 1
	fi
fi


echo "applying governor '$governor' on $NB_CPU CPUs"
let i=0
while [ $i -lt $NB_CPU ]
do
	echo $governor > /sys/devices/system/cpu/cpu$i/cpufreq/scaling_governor
	let i=$i+1
done


## todo
# CS script to launch it with high priority, performance mode, and return to ondemand when exiting

