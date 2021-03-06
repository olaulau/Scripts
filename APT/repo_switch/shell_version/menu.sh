#!/bin/bash
# Script : menu.sh


. /etc/lsb-release
#echo $DISTRIB_CODENAME

LANG_PREFIX=${LANG:0:2}  
#echo $LANG_PREFIX


ABSOLUTE_SCRIPT=`realpath "$0"`
cd "$(dirname "$ABSOLUTE_SCRIPT")" # for execution from another directory without having to cd (usefull for cron)





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
	#TODO création seulement si mode auto ou si accepté via menu
	echo "création du template d'après le fichier sources.list actuel"
	if [ $DISTRIB_ID = "LinuxMint" ]
	then
		cp /etc/apt/sources.list.d/official-package-repositories.list sources.list.template
	else
		cp /etc/apt/sources.list sources.list.template
	fi
	#cp sources.list.origin sources.list.template
	sed --in-place "s|http://$LANG_PREFIX.archive.ubuntu.com|http://archive.ubuntu.com|g" sources.list.template
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

# ubuntu
sed --in-place "s|archive.ubuntu.com|${PREFIXES[opt]}archive.ubuntu.com|g" sources.list
sed --in-place "s|archive.canonical.com|${PREFIXES[opt]}archive.canonical.com|g" sources.list
sed --in-place "s|extras.ubuntu.com|${PREFIXES[opt]}extras.ubuntu.com|g" sources.list
sed --in-place "s|security.ubuntu.com|${PREFIXES[opt]}security.ubuntu.com|g" sources.list
# linux mint :
sed --in-place "s|packages.linuxmint.com|${PREFIXES[opt]}packages.linuxmint.com|g" sources.list
sed --in-place "s|extra.linuxmint.com|${PREFIXES[opt]}extra.linuxmint.com|g" sources.list
#TODO : dans un fichier différent de sources.list ?



## vérifier qu'un miroir est up -> choix automatique au démarrage de la machine
wget --quiet --output-document Release http://${PREFIXES[opt]}archive.ubuntu.com/ubuntu/dists/$DISTRIB_CODENAME/Release
let status=$?
rm -f Release
if [ $status -ne 0 ]
then
	echo "ce dépot ne semble pas fonctionner (pour votre distribution), voulez-vous quand-même l'utiliser ?"
	select opt in "Oui" "Non"
	do
		if [ "$opt" = "Non" ]
		then
			rm -f sources.list
			exit 1
		elif [ "$opt" = "" ]
		then
			echo "Invalid option, try another one continue"
		else
			break
		fi
	done	
fi





## check for root and copy file if possible
if [[ $EUID -ne 0 ]]
then
	echo "Cannot copy file without root permission." 1>&2
	echo "next time try with ' sudo ' ?" 1>&2
	echo "this time you can finish the job yourself by typing ' sudo cp sources.list /etc/apt/ '"
	echo "don't forget to make a backup first : ' sudo cp /etc/apt/sources.list /etc/apt/sources.list.BAK '"
	exit 1
else
	if [ $DISTRIB_ID = "LinuxMint" ]
	then
		cp /etc/apt/sources.list.d/official-package-repositories.list /etc/apt/sources.list.d/official-package-repositories.list.BAK
		cp sources.list /etc/apt/sources.list.d/official-package-repositories.list
	else
		cp /etc/apt/sources.list /etc/apt/sources.list.BAK
		cp sources.list /etc/apt/
	fi
	echo "done !"
fi
echo "you can run ' apt-get update ' and use the choosen repository. BYE"
exit


## possibilité de chronométrer la récupération d'un fichier (packages par exemple ?)

