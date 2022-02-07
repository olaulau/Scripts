#!/bin/bash

# randomly rename a test category
#./randomize.sh

# load config file
source "./piwigo-scripts/CheckAndUpdatePiwigo/conf/piwigo.conf"

# prepare
find piwigo-scripts/ -name "*.sh" -execdir chmod u+x {} +

# exec
sessionCookiePath=$(realpath ./piwigo-scripts/PiwigoSession/sessionCookie.txt)
./piwigo-scripts/PiwigoSession/pwg.session.login.sh $url $username $password "$sessionCookiePath"
./piwigo-scripts/PiwigoSiteSynchronisation/pwg.site.sync.sh $url "$sessionCookiePath" $siteToSync
./piwigo-scripts/PiwigoSession/pwg.session.logout.sh $url "$sessionCookiePath"
