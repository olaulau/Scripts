#! /bin/bash
## backup data's script


## load config file from script directory
ABSOLUTE_SCRIPT=`realpath "$0"`
cd "$(dirname "$ABSOLUTE_SCRIPT")" # for execution from another directory without having to cd (usefull for cron)
. ./backup_datas.config.sh


## backup music
echo "----- backup music files -----"
MUSIC_SRC="$MUSIC_SRC/$MUSIC_SUBDIR"
MUSIC_DEST=$(printf %q "$MUSIC_DEST/$MUSIC_SUBDIR")

# echo \
$RSYNC -rptgoD \
	--copy-links --size-only \
	--delete-after --partial-dir=PARTIAL \
	--verbose --stats --progress --itemize-changes \
	$TEST "$MUSIC_SRC/" "$MUSIC_DEST/"
# exit


## backup photos
echo "----- backup photo files -----"
PHOTO_SRC="$PHOTO_SRC/$PHOTO_SUBDIR"
PHOTO_DEST=$(printf %q "$PHOTO_DEST/$PHOTO_SUBDIR")

# echo \
$RSYNC -rptgoD \
	--copy-links --size-only \
	--delete-after --partial-dir=PARTIAL \
	--verbose --stats --progress --itemize-changes \
	$TEST "$PHOTO_SRC/" "$PHOTO_DEST/"
# exit

$PHOTO_POST_SCRIPT
