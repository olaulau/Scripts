#!/bin/bash

# total number of processes
echo "`ps -aux | wc -l`     TOTAL"

# count of each processes (by complete command line)
#ps -e -oargs= | sort | uniq -c | sort -bgr | head -n20

# count of each processes (by executable)
ps -e -oargs= | cut -d' ' -f1 | sort | uniq -c | sort -bgr | head -n20

