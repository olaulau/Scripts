#! /bin/bash
## backup games config file EXAMPLE
## please copy / rename to backup_games.config.sh


# TEST=" --dry-run"

## general config
MIN_SIZE=100 # 100MB, to avoid rsyncing garbage of old deleted games over a clean backup

## backup linux steam games .acf files
LINUX_STEAM_SRC="/home/$USER/.local/share/Steam/SteamApps/"
LINUX_STEAM_DEST="/media/SHARED/games/SteamApps (linux)/"

## backup windows steam games .acf files
WINDOWS_STEAM_SRC="/mnt/windows/Program Files (x86)/Steam/SteamApps/"
WINDOWS_STEAM_DEST="/media/SHARED/games/SteamApps (windows)/"

## backup every windows origin game avaiiable
ORIGIN_SRC="/mnt/windows/Program Files (x86)/Origin Games/"
ORIGIN_DEST="/media/SHARED/games/OriginGames/"

## backup every windows origin game avaiiable
BETHESDA_SRC="/mnt/windows/Program Files (x86)/Bethesda.net Launcher/games/"
BETHESDA_DEST="/mnt/SHARED/games/Bethesda.net Launcher/games/"

