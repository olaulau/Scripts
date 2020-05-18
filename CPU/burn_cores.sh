#!/bin/bash

COMMAND='./burn.sh'
CORES=`cat /proc/cpuinfo | grep processor | wc -l`


PIDS=()
echo "launching $CORES CPU burns"
for (( cpt=1; cpt<=$CORES; cpt++ ))
do
	#echo $COMMAND
	$COMMAND &
	PIDS+=($!)
done


echo "waiting for burn processes to finish ..."
wait -n ${PIDS[*]}
STATUS=$?
#echo "STATUS = $STATUS"
echo "finished"


exit

