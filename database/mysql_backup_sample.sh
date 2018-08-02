#!/bin/sh

today=`date +%Y%m%d`
oldday=`date --d "14day ago" +%Y%m%d`
target_dir="/mysql_backup_target_dir"
db_dump="/usr/bin/mysqldump"
mysql="/usr/bin/mysql"
user="hogeuser"
host="hogehostname"
mysql_password="hogepassword"

function mk_dir(){
    if [ ! -e $target_dir/$today ]
    then
        mkdir -p $target_dir/$today/
    fi
}

function db_backup(){
    database_list="hogedatabase1
hogedatabase2
hogedatabase3"
    for i in $database_list
    do
        mkdir $target_dir/$today/${i}
        echo "database backup $i"
      for TABLE in `mysql -u${user} -p${mysql_password} -h${host} -N -s -e "show tables in ${i};"`; do
          if [ "$TABLE" = "special_hogetable" ]; then
            echo "only backup special_hogetable's data nearly one year"
            mysqldump -u ${user} -p${mysql_password} -h${host} ${i} $TABLE --quick --single-transaction --order-by-primary --master-data=2 --set-gtid-purged=OFF --where 'delete_date > DATE_SUB( CURDATE(),INTERVAL 365 DAY )' > $target_dir/$today/${i}/$TABLE.sql
          else
            mysqldump -u ${user} -p${mysql_password} -h${host} ${i} $TABLE --quick --single-transaction --order-by-primary --master-data=2 --set-gtid-purged=OFF > $target_dir/$today/${i}/$TABLE.sql
          fi
      done;
          cd $target_dir/$today/ && tar czvf ${i}.gz ${i} --remove-file
    done
}

function rm_dir(){
    if [ -e $target_dir/$oldday ]
    then
        rm -fr $target_dir/$oldday
    fi
}

mk_dir
db_backup
rm_dir