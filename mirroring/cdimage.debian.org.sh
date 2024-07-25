#!/bin/bash
## https://cdimage.debian.org/mirror/cdimage/archive/


## https://cdimage.debian.org/cdimage/

SRC="rsync://cdimage.debian.org"
DEST="./cdimage.debian.org"
DIRECTORIES=( \
	"cdimage/release/current/amd64" \
	"cdimage/release/current-live/amd64" \
	"cdimage/weekly-builds/amd64" \
	"cdimage/weekly-live-builds/amd64" )

for directory in "${DIRECTORIES[@]}"
do
	echo "$directory"
	mkdir -p $DEST/$directory
	rsync -avh --delete --delete-excluded \
		--exclude='*-cinnamon*' --exclude='*-kde*' --exclude='*-lxde*' --exclude='*-lxqt*' --exclude='*-mate*' --exclude='*-xfce*' --exclude='*-standard*' \
		--exclude='bt-*' --exclude='jigdo-*' --exclude='list-*' --exclude='log' \
		--exclude='*.contents' --exclude='*.log' --exclude='*.packages' \
		--progress --itemize-changes --stats \
		"$SRC/$directory/" "$DEST/$directory/"
done


## unstable mini iso 
## https://wiki.debian.org/fr/DebianUnstable#Installation
## https://d-i.debian.org/daily-images/amd64/daily/netboot/mini.iso

SRC="https://d-i.debian.org"
DEST="./d-i.debian.org"
DIRECTORY="daily-images/amd64/daily/netboot"
FILE="mini.iso"

mkdir -p $DEST/$DIRECTORY
wget --content-disposition $SRC/$DIRECTORY/$FILE --output-document=$DEST/$DIRECTORY/$FILE


## symlinks for stable
SRC="./cdimage.debian.org"
DEST="../downloads/OS/linux/debian"

rm -f "$DEST/stable"*/*
ln -f -s -t "$DEST/stable"*/ "$SRC/cdimage/release/current-live/amd64/iso-hybrid/debian-live-"*-amd64-gnome.iso
ln -f -s -t "$DEST/stable"*/ "$SRC/cdimage/release/current/amd64/iso-dvd/debian-"*-amd64-DVD-1.iso
ln -f -s -t "$DEST/stable"*/ "$SRC/cdimage/release/current/amd64/iso-cd/debian-"??.?.?-amd64-netinst.iso

