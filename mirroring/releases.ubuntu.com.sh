#!/bin/bash

SRC="rsync://ftp.crifo.org/ubuntu-cd"
DEST="./releases.ubuntu.com"

rsync -avh --delete --delete-excluded \
	--delete-excluded --exclude '*.torrent' --exclude '*.zsync' --exclude '*.list' --exclude '*.manifest' --exclude '*.metalink' --exclude '*.jigdo' --exclude '*.template' --exclude '*-metalink*' --exclude '*-i386.iso' \
	--progress --itemize-changes --stats \
	"$SRC/" "$DEST/"

