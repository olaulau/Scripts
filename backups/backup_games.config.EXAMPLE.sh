#! /bin/bash
## backup games config file EXAMPLE
## please copy / rename to backup_games.config.sh


# TEST=" --dry-run"

## backup linux steam games .acf files
LINUX_STEAM_SRC="/home/$USER/.local/share/Steam/SteamApps/"
LINUX_STEAM_DEST="/media/SHARED/games/SteamApps (linux)/"

## backup windows steam games .acf files
WINDOWS_STEAM_SRC="/mnt/windows/Program Files (x86)/Steam/SteamApps/"
WINDOWS_STEAM_DEST="/media/SHARED/games/SteamApps (windows)/"

## backup every windows origin game avaiiable
ORIGIN_SRC="/mnt/windows/Program Files (x86)/Origin Games/"
ORIGIN_DEST="/media/SHARED/games/OriginGames/"

