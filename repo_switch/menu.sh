#!/bin/bash
# Script : menu.sh


. /etc/lsb-release
echo $DISTRIB_CODENAME

LANG_PREFIX=${LANG:0:2}  
echo $LANG_PREFIX

exit


## créer un template s'il n'existe pas
if [ -f sources.list.template ]
then
	cp /etc/apt/sources.list sources.list.template
fi


## vérif présence fichier de config


## premier menu choix action (auto oupas)


## 2nd menu (si choix manuel)
PS3="Dépôt ? "
select choix in \
   "ubuntu fr" \
   "d-l.fr"  \
   "local"	\
   "quitter"
do
   case $REPLY in
      1) echo "Ubuntu FR"
      	break ;;
      2) echo "mirrors.d-l.fr !"
      	break ;;
      3) echo "Local !!!"
         break ;;
      q) echo "bye ..."
      	exit ;;
      *) echo "Choix invalide"
   esac
done


##passer plutot par un fichier temporaire
rm -f sources.list
cp sources.list.template sources.list

sed --in-place "s|fr.archive.ubuntu.com|mirrors.d-l.fr/archive.ubuntu.com|g" sources.list
sed --in-place "s|archive.canonical.com|mirrors.d-l.fr/archive.canonical.com|g" sources.list
sed --in-place "s|extras.ubuntu.com|mirrors.d-l.fr/extras.ubuntu.com|g" sources.list

#cat sources.list

#exit





## vérifier qu'un miroir est up -> choix automatique au démarrage de la machine
wget http://mirrors.d-l.fr/archive.ubuntu.com/ubuntu/dists/raring/Release
let dl-state = $?
rm -f Release
si dl-state == 0 dans ce cas le dépot a l'air OK


## possibilité de chronométrer la récupération d'un fichier (packages par exemple ?)

