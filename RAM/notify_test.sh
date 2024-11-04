#!/bin/bash

if [ -r "$HOME/.dbus/Xdbus" ]; then
  . "$HOME/.dbus/Xdbus"
fi

/usr/bin/notify-send --icon="/usr/share/icons/gnome/256x256/status/dialog-warning.png" --urgency=normal --expire-time=5000 --app-name="RAM" "titre du test" "contenu du test"
#/usr/bin/notify-send --icon="/usr/share/icons/gnome/256x256/status/dialog-warning.png" --urgency=critical --app-name="RAM" "titre du test" "contenu du test"

