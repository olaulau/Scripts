#!/bin/bash

PROCESS_NAME="Counter-Strike"
NICE=-5


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

