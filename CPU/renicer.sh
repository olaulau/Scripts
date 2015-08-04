#!/bin/bash

## config vars
PROCESS_NAME="Counter-Strike"
NICE=-5


## check if root is needed
if [[ $NICE -lt 0 ]]
then
	if [[ $EUID -ne 0 ]]
	then
	   echo "This script must be run as root." 1>&2
	   echo "Maybe try with ' sudo ' ?" 
	   exit 1
	fi
fi


PIDS_ARRAY=()

## parents
PIDS=( $(ps -eLf | grep -v "grep" | grep "$PROCESS_NAME" | tr -s ' ' | cut -d' ' -f3 | uniq) )
PIDS_ARRAY+=(${PIDS[@]})

## processes
PIDS=( $(ps -eLf | grep -v "grep" | grep "$PROCESS_NAME" | tr -s ' ' | cut -d' ' -f2 | uniq) )
PIDS_ARRAY+=(${PIDS[@]})

## threads
PIDS=( $(ps -eLf | grep -v "grep" | grep "$PROCESS_NAME" | tr -s ' ' | cut -d' ' -f4 | uniq) )
PIDS_ARRAY+=(${PIDS[@]})




## renicing
for pid in "${PIDS_ARRAY[@]}"
do
	echo " *** $pid *** "
	renice -n $NICE $pid
done


exit

