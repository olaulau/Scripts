#!/bin/bash

#SRC="rsync://cdimage.ubuntu.com/cdimage"
SRC="rsync://ftp.crifo.org/ubuntu-cd/"
DEST="./cdimage.ubuntu.com"

rsync -avh --delete --delete-excluded \
	--exclude '*.torrent' --exclude '*.zsync' --exclude '*.list' --exclude '*.manifest' \
	--progress --itemize-changes --stats \
	"$SRC/" "$DEST/"

