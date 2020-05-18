#! /bin/bash

## requirements : sensors
# apt install lm-sensors
# sensors-detect


TMP_FILE=/tmp/$RANDOM
sensors > $TMP_FILE


TEMP=`cat $TMP_FILE | grep "°C" | grep "Core" | head -n1`  # intel
if [ "$TEMP" == "" ]
then # amd
	TEMP=`cat $TMP_FILE | grep "°C" | grep "Tdie"` # intel
fi
TEMP=`echo $TEMP | grep -oP '([+-]\d+\.\d)°C'`

GOVERNOR=`cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_governor`

FREQ=`cat /proc/cpuinfo  | grep MHz | head -n1 | tr -s ' ' | cut -d' ' -f3,4`
## faire la moyenne ?

echo "$TEMP  |  $GOVERNOR  |  $FREQ MHz"


rm $TMP_FILE
exit

