## crontab
# ispconfig
#*   *   *   *   *   /root/bin/ispconfig_cron.sh 2>&1 >> /root/bin/ispconfig_cron.log

## config
max_duration=60         # 1m
max_interval=20         # 20s


## init
echo " "
date
start=`date +%s`
last_exec=0


## core loop
total_duration=0
while [ $total_duration -lt $max_duration ] 
do
        current=`date +%s`
        let current_duration=$current-$last_exec
        if [ $current_duration -ge $max_interval ]
        then
                #echo "execution ..."
                last_exec=`date +%s`
                /usr/local/ispconfig/server/server.sh
                /usr/local/ispconfig/server/cron.sh
                #sleep 2
        fi
        sleep 1
        current=`date +%s`
        let total_duration=$current-$start
done
#echo "end"

