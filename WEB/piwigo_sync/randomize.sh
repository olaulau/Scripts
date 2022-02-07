#!/bin/bash

# load config file
source "./piwigo-scripts/CheckAndUpdatePiwigo/conf/piwigo.conf"

# randomly rename a test category inside pwg galleryh
rand_str=`echo $RANDOM | md5sum | head -c 20`
cd $piwigoDir/TEST
mv * $rand_str
