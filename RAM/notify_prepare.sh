#!/bin/bash

# has to be launched one per session
# 
# then call this code in your cron script
#	if [ -r "$HOME/.dbus/Xdbus" ]; then
#	  . "$HOME/.dbus/Xdbus"
#	fi

mkdir -p $HOME/.dbus/
touch $HOME/.dbus/Xdbus
chmod 600 $HOME/.dbus/Xdbus
env | grep DBUS_SESSION_BUS_ADDRESS > $HOME/.dbus/Xdbus
echo 'export DBUS_SESSION_BUS_ADDRESS' >> $HOME/.dbus/Xdbus
#cat $HOME/.dbus/Xdbus
#exit

exit 0

