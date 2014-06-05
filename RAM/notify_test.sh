#!/bin/bash

if [ -r "$HOME/.dbus/Xdbus" ]; then
  . "$HOME/.dbus/Xdbus"
fi

/usr/bin/notify-send "titre du test" "contenu du test"

