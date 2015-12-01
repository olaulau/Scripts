#! /bin/bash
## backup data's config script
## please copy / rename to backup_datas.config.sh

RSYNC="/usr/local/bin/rsync_patched --rsync-path=/usr/local/bin/rsync_patched"
# TEST=" --dry-run "


## backup music
MUSIC_SUBDIR=""
MUSIC_SRC="/home/laulau/Music"
MUSIC_DEST="laulau@srv:/media/raid1/home/laulau/sauvegardes/musique"


## backup photos
PHOTO_SUBDIR=""
PHOTO_SRC="/home/laulau/Pictures/photos_numeriques/"
PHOTO_DEST="laulau@srv:/media/raid1/home/laulau/sauvegardes/stockage_perso/photos_numeriques"

