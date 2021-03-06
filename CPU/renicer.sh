#!/bin/bash


## config vars
PROCESS_NAME="Counter-Strike"
NICE=-5


## check root
sudo -v


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

# filtering duplicates
#echo ${PIDS_ARRAY[*]}
PIDS_ARRAY=( $(
    for el in "${PIDS_ARRAY[@]}"
    do
        echo "$el"
    done | sort | uniq) )
#echo ${PIDS_ARRAY[*]}


## renicing
let changed=0
let unchanged=0
for pid in "${PIDS_ARRAY[@]}"
do
	#echo -e
	#echo " *** $pid *** "
	result=$(sudo renice -n $NICE $pid)
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
if [[ $changed -eq 0 ]] && [[ $unchanged -eq 0 ]]
	then echo "nothing to do"
fi
exit

