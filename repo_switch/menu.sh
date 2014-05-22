#!/bin/bash
# Script : menu.sh


. /etc/lsb-release
#echo $DISTRIB_CODENAME

LANG_PREFIX=${LANG:0:2}  
#echo $LANG_PREFIX







## vérif présence fichier de config
if [ ! -f config.sh ]
then
	echo "config.sh pas trouvé."
	echo "vous pouvez prendre exemple sur le config.sh.EXAMPLE fourni."
	exit
fi

## chargement config et vérif
. ./config.sh
if ! [[ ${#TITLES[*]} -gt 0  &&  ${#TITLES[*]} -eq ${#PREFIXES[*]} ]]
then
	echo "fichier de config incorrect."
	exit
fi

let NB=${#TITLES[*]}
#echo "$NB éléments"
#for i in ${!TITLES[*]}
#do
#	echo "${TITLES[i]} => ${PREFIXES[i]}"
#done



## créer un template s'il n'existe pas
if [ ! -f sources.list.template ]
then
	#TODO création seulemnt si mode auto ou siaccepté via menu
	echo "création du template d'après le fichier sources.list actuel"
	cp /etc/apt/sources.list sources.list.template
fi


## premier menu choix action (auto oupas)




## 2nd menu (si choix manuel)

#TODO refaire le menu avec do-while, for, read (permettra d'éviter de construire le tableau CHOIX en récupérant directement l'indice saisi)
CHOIX=""
for ((i=1; i<=NB; i++))
do
   	CHOIX[$i-1]="$i ${TITLES[$i]}"
done

PS3="Dépôt ? "
select opt in "${CHOIX[@]}" "Quit"
do
	if [ "$opt" = "Quit" ]
	then
		echo "Goodbye!"
		exit
	elif [ "$opt" = "" ]
	then
		echo "Invalid option, try another one continue"
	else
		break
	fi
done

opt=`echo "$opt" | cut -d' ' -f1`
#echo $opt
#echo "${TITLES[opt]} => ${PREFIXES[opt]}"
#exit


#TODO utiliser un système de variables à remplacer dans le template
#TODO passer plutot par un fichier temporaire
rm -f sources.list
cp sources.list.template sources.list

sed --in-place "s|fr.archive.ubuntu.com|${PREFIXES[opt]}archive.ubuntu.com|g" sources.list
sed --in-place "s|archive.canonical.com|${PREFIXES[opt]}archive.canonical.com|g" sources.list
sed --in-place "s|extras.ubuntu.com|${PREFIXES[opt]}extras.ubuntu.com|g" sources.list

#echo "done !"
#exit

#cat sources.list

#exit





## vérifier qu'un miroir est up -> choix automatique au démarrage de la machine
wget --quiet --output-document Release http://${PREFIXES[opt]}archive.ubuntu.com/ubuntu/dists/$DISTRIB_CODENAME/Release
let status=$?
rm -f Release
if [ $status -ne 0 ]
then
	echo "le dépot ne semble pas fonctionner, abandon."
	rm -f sources.list
	exit
fi

echo "done !"
exit


## possibilité de chronométrer la récupération d'un fichier (packages par exemple ?)

