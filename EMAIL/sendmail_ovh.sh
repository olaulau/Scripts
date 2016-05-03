#! /bin/bash

## http://www.commentcamarche.net/contents/536-pop3-smtp-imap-protocoles-de-messagerie


## user config
SMTP_USER=""
SMTP_PASSWORD=""
EHLO_HOST=""
SENDER=""
REPLY_TO=$SENDER


## technical config
SMTP_HOST="ssl0.ovh.net"
SMTP_PORT="465"
ENCODING="quoted-printable"


## cli params
RECIPIENT=$1
SUBJECT=$2
BODY=$3


## send
BODY=`echo "$BODY" | iconv -t ISO-8859-15 -f UTF-8`

./smtp-cli --verbose \
--server="$SMTP_HOST" --port="$SMTP_PORT" --ssl \
--force-ehlo --hello-host="$EHLO_HOST" --text-encoding $ENCODING \
--enable-auth --user "$SMTP_USER" --pass "$SMTP_PASSWORD" \
--from "$SENDER" --to "$RECIPIENT" \
--subject "$SUBJECT" --body-plain "$BODY" \

