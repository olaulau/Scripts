#!/bin/bash

# ps -e -o rss,comm | termsql --separator "\t" --columns 'rss,comm' "SELECT SUM(rss)/1024 AS ram,comm FROM tbl GROUP BY comm ORDER BY ram DESC LIMIT 10"
ps -e -orss=,args= | awk '{ printf("%d %s\n", $1/1024, $2)  }' | termsql "select sum(col0), col1 group by col1 order by sum(col0) DESC LIMIT 20"

