#! /bin/bash

cd "$(dirname "$0")" # for execution from another directory without aving to cd (usefull for cron)
. ./piwigo_sync.conf.sh


mkdir -p /tmp/$USER/
COOKIE_FILE="/tmp/$USER/piwigo_sync_cookie.txt"
OUTPUT_FILE="/tmp/$USER/out_log.txt"
OUTPUT_DOCUMENT="/tmp/$USER/out_doc.txt"


ACTION="identification.php"

POST_DATA="username=$PIWIGO_LOGIN&password=$PIWIGO_PASSWORD&redirect=&login=Valider"

wget --server-response --output-file=$OUTPUT_FILE --output-document=$OUTPUT_DOCUMENT --load-cookies $COOKIE_FILE --save-cookies $COOKIE_FILE --keep-session-cookies --max-redirect=0 --post-data "$POST_DATA" $PIWIGO_BASE_URL/$ACTION

#cat $OUTPUT_FILE
rm -r $OUTPUT_FILE
#cat $OUTPUT_DOCUMENT
rm -r $OUTPUT_DOCUMENT
#cat $COOKIE_FILE


ACTION="/admin.php?page=site_update&site=1"

POST_DATA="sync=files&display_info=1&add_to_caddie=1&privacy_levell=0&sync_meta=&simulate=0&subcats-included=1&submit-included=1&submit=Syncronisation+Rapide"

wget --server-response --output-file=$OUTPUT_FILE --output-document=$OUTPUT_DOCUMENT --load-cookies $COOKIE_FILE --save-cookies $COOKIE_FILE --keep-session-cookies --max-redirect=0 --post-data "$POST_DATA" $PIWIGO_BASE_URL/$ACTION

#cat $OUTPUT_FILE
rm -r $OUTPUT_FILE
#cat $OUTPUT_DOCUMENT
rm -r $OUTPUT_DOCUMENT
#cat $COOKIE_FILE


exit

