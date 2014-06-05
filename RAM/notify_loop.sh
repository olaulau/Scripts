#!/bin/bash

while [ 1 ]
do
	#~/BIN/notify_test.sh
	~/BIN/RAM.sh
	sleep 1
	killall notify-osd
	sleep 1
done

