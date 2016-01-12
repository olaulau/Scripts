#! /bin/bash
## backup data's config script
## please copy / rename to backup_datas.config.sh

RSYNC="/usr/local/bin/rsync_patched --rsync-path=/usr/local/bin/rsync_patched"
# TEST=" --dry-run "


## backup music
MUSIC_SUBDIR=""
MUSIC_SRC="/home/$USER/Music"
MUSIC_DEST="$USER@srv:/home/$USER/sauvegardes/musique"


## backup photos
PHOTO_SUBDIR=""
PHOTO_SRC="/home/$USER/Pictures/photos_numeriques/"
PHOTO_DEST="$USER@srv:/home/$USER/sauvegardes/stockage_perso/photos_numeriques"

