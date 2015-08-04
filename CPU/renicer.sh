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
	   echo "Maybe try with ' sudo ' ?" 1>&2
	   exit 1
	fi
fi


## init
PIDS_ARRAY=()


## fetching datas
# parents
PIDS=( $(ps -eLf | grep -v "grep" | grep "$PROCESS_NAME" | tr -s ' ' | cut -d' ' -f3 | uniq) )
PIDS_ARRAY+=(${PIDS[@]})

# processes
PIDS=( $(ps -eLf | grep -v "grep" | grep "$PROCESS_NAME" | tr -s ' ' | cut -d' ' -f2 | uniq) )
PIDS_ARRAY+=(${PIDS[@]})

# threads
PIDS=( $(ps -eLf | grep -v "grep" | grep "$PROCESS_NAME" | tr -s ' ' | cut -d' ' -f4 | uniq) )
PIDS_ARRAY+=(${PIDS[@]})


## renicing
let changed=0
let unchanged=0
for pid in "${PIDS_ARRAY[@]}"
do
	#echo -e
	#echo " *** $pid *** "
	result=$(renice -n $NICE $pid)
	#echo $result
	old=$(echo $result | cut -d' ' -f6 | tr -d ',')
	new=$(echo $result | cut -d' ' -f9)
	#echo "$old $new"
	
	if [[ $old -ne $new ]]
	then
		let changed=$changed+1
	else
		let unchanged=$unchanged+1
	fi
done

#echo -e
if [[ $changed -gt 0 ]]
	then echo "$changed processes/threads reniced"
fi

if [[ $unchanged -gt 0 ]]
	then echo "$unchanged processes/threads with unchanged priority"
fi

exit

