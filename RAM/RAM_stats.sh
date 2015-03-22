#!/bin/bash

COLUMNS=$(tput cols)
ps -e -orss=,args= | sort -r -b -n | /usr/bin/pr -TW$COLUMNS | head

## TODO
# ajouter PID avant la commande
# commande : couper après premier espace, et couper avant dernier slash
#  -> ne garder que firefox, jav a, csgo_linux


## faire un autre script qui lance ca périodiquement vers une db
## et un autre qui génère des graphs pour permettre d'analyser ce qu'il se passe.
