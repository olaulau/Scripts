#! /bin/bash

TMP_FILE=/tmp/$RANDOM
sensors > $TMP_FILE

TEMP=`cat $TMP_FILE | grep "CPU Temperature:" | tr -s ' ' | cut -d' ' -f3`
POWER=`cat $TMP_FILE | grep "power1:" | tr -s ' ' | cut -d' ' -f2`
FREQ=`cat /proc/cpuinfo  | grep MHz | head -n1 | tr -s ' ' | cut -d' ' -f3,4`
## faire la moyenne ?

echo "$TEMP  |  $POWER W  |  $FREQ MHz"


rm $TMP_FILE
exit

