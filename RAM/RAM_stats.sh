#!/bin/bash

COLUMNS=$(tput cols)
ps -e -orss=,args= | sort -r -b -n | head -n10 | awk '{ printf("%d %s\n", $1/1024, $2)  }'
# | /usr/bin/pr -TW$COLUMNS

## TODO
# ajouter PID avant la commande
# commande : couper après premier espace, et couper avant dernier slash
#  -> ne garder que firefox, java, csgo_linux


## faire un autre script qui lance ca périodiquement vers une db
## et un autre qui génère des graphs pour permettre d'analyser ce qu'il se passe.

