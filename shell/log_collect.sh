#!/bin/sh

# MIT License
# Author: wang.yubo@dragon.jp
# Add this script to cron like below
# 0 0 * * * /usr/bin/bash /your/script/save/space/log_collect.sh > /dev/null 2>&1

# workspace
WORK_DIR="/your/website/log/save/space/"

# log export path
LOG_DIR="${WORK_DIR}collect_logs_day/"

# param　date format YYYY-MM-DD
LOG_DATE=`date --date "1 day ago" "+%Y-%m-%d"`
#LOG_DATE=$1

# log file name
LOGFILE="${LOG_DIR}${LOG_DATE}.log"

#create log folder is not exist
if [ ! -e $LOG_DIR ]; then mkdir -m 777 -p $LOG_DIR; fi

#collect yesterday log
#begin

#collect access log
if [ -e ${WORK_DIR}access.log ]; then
    echo "${LOG_DATE}'s access log start" >> $LOGFILE
    less ${WORK_DIR}access.log | grep $LOG_DATE >> $LOGFILE
    echo "${LOG_DATE}'s access log end" >> $LOGFILE
fi

#collect error log
if [ -e ${WORK_DIR}error.log ]; then
    echo "${LOG_DATE}'s error log start" >> $LOGFILE
    less ${WORK_DIR}error.log | grep $LOG_DATE >> $LOGFILE
    echo "${LOG_DATE}'s error log end" >> $LOGFILE
fi

#collect debug log
if [ -e ${WORK_DIR}debug.log ]; then
    echo "${LOG_DATE}'s debug log start" >> $LOGFILE
    less ${WORK_DIR}debug.log | grep $LOG_DATE >> $LOGFILE
    echo "${LOG_DATE}'s debug log end" >> $LOGFILE
fi
#end
