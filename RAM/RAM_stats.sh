#!/bin/bash

ps -e -o rss,comm | termsql --separator "\t" --columns 'rss,comm' "SELECT SUM(rss)/1024 AS ram,comm FROM tbl GROUP BY comm ORDER BY ram DESC LIMIT 10"
