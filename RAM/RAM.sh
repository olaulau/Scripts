#! /bin/bash

# you can put this script in a crontab like that : 
# */5     *       *       *       *       ~/BIN/RAM.sh
# only if you called notify_prepare.sh in your session

if [ -r "$HOME/.dbus/Xdbus" ]; then
  . "$HOME/.dbus/Xdbus"
fi

let FREE_RAM=`free -m | head -n3 | tail -n1 | tr -s ' ' | cut -f4 -d' '`
###let USED_SWAP=`free -m | head -n4 | tail -n1 | tr -s ' ' | cut -f3 -d' '`
let DELTA=$FREE_RAM
###-$USED_SWAP
#echo $DELTA

let THRESHOLD=800
if [ "$DELTA" -lt "$THRESHOLD" ]
then
	/usr/bin/notify-send "RAM" "ATTENTION : il ne reste que $DELTA Mo de libre"
#else
#	/usr/bin/notify-send "RAM" "ca va : il reste $DELTA Mo de libre"
fi

