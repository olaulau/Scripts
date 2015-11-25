#! /bin/bash
## backup games script



## load config file from script directory
ABSOLUTE_SCRIPT=`realpath "$0"`
cd "$(dirname "$ABSOLUTE_SCRIPT")" # for execution from another directory without having to cd (usefull for cron)
. ./backup_games.config.sh



## backup linux steam games .acf files
echo "----- linux steam : *.acf files -----"
rsync -lptgoD --recursive \
--include=*.acf --exclude=* \
--verbose --stats --progress \
$TEST "$LINUX_STEAM_SRC/" "$LINUX_STEAM_DEST/"


## backup every linux steam game avaiiable
SRC="$LINUX_STEAM_SRC/common/"
DEST="$LINUX_STEAM_DEST/common/"
cd "$SRC"
for GAME in */
do
	echo "----- linux steam : $GAME -----"
	rsync -a --delete --no-whole-file --verbose --stats --progress $TEST "$SRC/${GAME}/" "$DEST/${GAME}/"
done



## backup windows steam games .acf files
echo "----- windows steam : *.acf files -----"
rsync \
-lptgoD \
--recursive \
--include=*.acf --exclude=* \
--verbose --stats --progress \
$TEST "$WINDOWS_STEAM_SRC/" "$WINDOWS_STEAM_DEST/"


## backup every windows steam game avaiiable
SRC="$WINDOWS_STEAM_SRC/common/"
DEST="/$WINDOWS_STEAM_DEST/common/"
cd "$SRC"
for GAME in */
do
	echo "----- windows steam : $GAME -----"
	rsync -a --delete --no-whole-file --verbose --stats --progress $TEST "$SRC/${GAME}/" "$DEST/${GAME}/"
done



## backup every windows origin game avaiiable
cd "$ORIGIN_SRC"
for GAME in */
do
	echo "----- origin : $GAME -----"
	rsync -a --delete --no-whole-file --verbose --stats --progress $TEST "$ORIGIN_SRC/${GAME}/" "$ORIGIN_DEST/${GAME}/"
done

