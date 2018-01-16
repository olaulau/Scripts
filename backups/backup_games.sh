#! /bin/bash
## backup games script



## load config file from script directory
ABSOLUTE_SCRIPT=`realpath "$0"`
cd "$(dirname "$ABSOLUTE_SCRIPT")" # for execution from another directory without having to cd (usefull for cron)
. ./backup_games.config.sh



## $1 : linux steam library directory
function backup_linux_steam_library {
	## backup linux steam games .acf files
	echo "----- linux steam : *.acf files -----"
	mkdir -p "$LINUX_STEAM_DEST/"
	rsync -lptgoD --recursive \
	--include=*.acf --exclude=* \
	--verbose --stats --progress \
	$TEST "$1/" "$LINUX_STEAM_DEST/"


	## backup every linux steam game avaiiable
	SRC="$1/common/"
	DEST="$LINUX_STEAM_DEST/common/"
	cd "$SRC"
	for GAME in */
	do
		echo "----- linux steam : $GAME -----"
		SIZE=`du -sm "$SRC/${GAME}/" | cut -f1`
		if [ "$SIZE" -ge "$MIN_SIZE" ]
		then
			mkdir -p "$DEST/${GAME}/"
			rsync -a --delete --no-whole-file --verbose --stats --progress $TEST "$SRC/${GAME}/" "$DEST/${GAME}/"
		else
			echo "size is too small, no backup to avoid rsyncing garbage of old deleted games over a clean backup"
		fi
	done
}



## backup linux steam primary library
backup_linux_steam_library $LINUX_STEAM_SRC



## try to locate others linux steam libraries and backup them
echo "libraries : "
for library in `cat $LINUX_STEAM_SRC/libraryfolders.vdf | grep -P -o '"[0-9]*"\t+".*"' | cut -d '"' -f4`
do
	backup_linux_steam_library $library/SteamApps/
done







## backup windows steam games .acf files
echo "----- windows steam : *.acf files -----"
mkdir -p "$WINDOWS_STEAM_DEST/"
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
	mkdir -p "$DEST/${GAME}/"
	rsync -a --delete --no-whole-file --verbose --stats --progress $TEST "$SRC/${GAME}/" "$DEST/${GAME}/"
done



## backup every windows origin game avaiiable
cd "$ORIGIN_SRC"
for GAME in */
do
	echo "----- origin : $GAME -----"
	mkdir -p "$ORIGIN_DEST/${GAME}/"
	rsync -a --delete --no-whole-file --verbose --stats --progress $TEST "$ORIGIN_SRC/${GAME}/" "$ORIGIN_DEST/${GAME}/"
done



## backup every windows bethesda game avaiiable
cd "$BETHESDA_SRC"
for GAME in */
do
	echo "----- origin : $GAME -----"
	mkdir -p "$BETHESDA_DEST/${GAME}/"
	rsync -a --delete --no-whole-file --verbose --stats --progress $TEST "$BETHESDA_SRC/${GAME}/" "$BETHESDA_DEST/${GAME}/"
done

