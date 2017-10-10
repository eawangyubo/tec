### Docker:
```
docker run --privileged -d -p 80:80 -p 443:443 -p 3306:3306  --name centos7 centos:7 /sbin/init
docker exec -it centos7 bash
```

### Linux:
```
cp -p /usr/share/zoneinfo/Japan /etc/localtime
yum install epel-release -y
yum -y update epel-release
```

### Nginx:
```
yum install -y http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
rpm -ivh http://nginx.org/packages/centos/7/noarch/RPMS/nginx-release-centos-7-0.el7.ngx.noarch.rpm
yum -y update nginx-release-centos
yum -y --enablerepo=nginx install nginx
systemctl start nginx.service
```

### Mysql:

#### Server側操作：
```
rpm -qa | grep maria
yum remove mariadb-libs
yum localinstall http://dev.mysql.com/get/mysql57-community-release-el7-7.noarch.rpm
yum install -y mysql-community-server
mysql --version
systemctl enable mysqld.service
systemctl start mysqld.service
cat /var/log/mysqld.log | grep 'temporary password'
```

#### Mysql側操作:
```
mysql -u root -p

#root パスワード変更について、下記参考：
#https://qiita.com/RyochanUedasan/items/9a49309019475536d22a
SET GLOBAL validate_password_length=4;
SET GLOBAL validate_password_policy=LOW;
set password for root@localhost=password('XXXXXXXX');

#アプリ用アカウントを作成する：
CREATE USER "username"@"%" IDENTIFIED WITH mysql_native_password BY "XXXXXXXXXXX";
GRANT ALL PRIVILEGES ON *.* TO "username"@"%";
```

### PHP:
```
Install:
yum --enablerepo=remi,remi-php70  install php php-cli php-devel php-common php-fpm php-mbstring php-mysqli php-mcrypt php-apc php-opcache 
 php-xml php-pecl-zip php-gd 

Version confirm:
php -v 

サービス有効化:
systemctl enable php-fpm

起動:
systemctl start php-fpm

ステータス確認:
systemctl status php-fpm
```

### Virtualhost sample(eccube3):
```
server {
    listen       80;
    server_name  www.sample.jp;
    root   /var/www/html/www.sample.jp/html;
    index  index.php;
    access_log  /var/log/nginx/www.sample.jp.access.log  main;
    error_log   /var/log/nginx/www.sample.jp.error.log warn;

    error_page   500 502 503 504  /50x.html;

    location / {
        index index.php;
        try_files $uri $uri/ /index.php?u=$uri&$args;
    }

    location = /50x.html {
        root   /usr/share/nginx/html;
    }
    
    #only for eccube3 install start
    location ^~ /install.php {
        root           /var/www/html/www.sample.jp/html;
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;

        include        fastcgi_params;
    }
    #only for eccube3 install end

    location ~ \.php$ {
        root           /var/www/html/www.sample.jp/html;
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }

    location ~ /\.ht {
        deny  all;
    }
}
```