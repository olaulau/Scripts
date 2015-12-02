#! /bin/bash


## load config file for execution from another directory without having to cd (usefull for cron)
cd "$(dirname "$0")"
. ./piwigo_sync.conf.sh



## prepare things
TEMP=`mktemp -d`
COOKIE_FILE="$TEMP/piwigo_sync_cookie.txt"
OUTPUT_FILE="$TEMP/out_log.txt"
OUTPUT_DOCUMENT="$TEMP/out_doc.txt"



## get cookie from home page
ACTION="index.php"
wget --server-response --output-file=$OUTPUT_FILE --output-document=$OUTPUT_DOCUMENT --load-cookies $COOKIE_FILE --save-cookies $COOKIE_FILE --keep-session-cookies --max-redirect=0 $PIWIGO_BASE_URL/$ACTION

#cat $OUTPUT_FILE
rm -f $OUTPUT_FILE
#cat $OUTPUT_DOCUMENT
rm -f $OUTPUT_DOCUMENT
#cat $COOKIE_FILE



## authentification
ACTION="identification.php"
POST_DATA="username=$PIWIGO_LOGIN&password=$PIWIGO_PASSWORD&redirect=&login=Valider"
wget --server-response --output-file=$OUTPUT_FILE --output-document=$OUTPUT_DOCUMENT --load-cookies $COOKIE_FILE --save-cookies $COOKIE_FILE --keep-session-cookies --max-redirect=0 --post-data "$POST_DATA" $PIWIGO_BASE_URL/$ACTION

#cat $OUTPUT_FILE
rm -f $OUTPUT_FILE
#cat $OUTPUT_DOCUMENT
rm -f $OUTPUT_DOCUMENT
#cat $COOKIE_FILE



## site update
ACTION="/admin.php?page=site_update&site=1"
POST_DATA="sync=files&display_info=1&add_to_caddie=1&privacy_levell=0&sync_meta=&simulate=0&subcats-included=1&submit-included=1&submit=Syncronisation+Rapide"
wget --server-response --output-file=$OUTPUT_FILE --output-document=$OUTPUT_DOCUMENT --load-cookies $COOKIE_FILE --save-cookies $COOKIE_FILE --keep-session-cookies --max-redirect=0 --post-data "$POST_DATA" $PIWIGO_BASE_URL/$ACTION

#cat $OUTPUT_FILE
rm -f $OUTPUT_FILE
#cat $OUTPUT_DOCUMENT
rm -f $OUTPUT_DOCUMENT
#cat $COOKIE_FILE



rm -f $COOKIE_FILE
rmdir $TEMP
exit

