#! /bin/bash

# you can put this script in a crontab like that : 
# */5     *       *       *       *       ~/BIN/RAM.sh
# only if you called notify_prepare.sh in your session

if [ -r "$HOME/.dbus/Xdbus" ]; then
  . "$HOME/.dbus/Xdbus"
fi

let AVAILABLE_RAM=`free -m | head -n2 | tail -n1 | tr -s ' ' | cut -f7 -d' '`
###let USED_SWAP=`free -m | head -n3 | tail -n1 | tr -s ' ' | cut -f3 -d' '`
let DELTA=$AVAILABLE_RAM
###-$USED_SWAP
#echo $DELTA

let THRESHOLD=1500
if [ "$DELTA" -lt "$THRESHOLD" ]
then
	id=`cat RAM.data`
	/usr/bin/notify-send --print-id --replace-id $id --icon="/usr/share/icons/gnome/256x256/devices/computer.png" --urgency=normal --expire-time=5000 --app-name="RAM" "RAM" "ATTENTION : il ne reste que $DELTA Mo de libre" > RAM.data
#else
#	/usr/bin/notify-send "RAM" "ca va : il reste $DELTA Mo de libre"
fi

