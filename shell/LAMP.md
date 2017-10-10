### Docker:

```
docker run --privileged -d -p 80:80 -p 443:443 -p 3306:3306  --name centos7-lamp centos:7 /sbin/init
docker exec -it centos7-lamp bash
```

### Linux:
```
yum update -y
yum install -y epel-release 
yum install -y http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
cp -p /usr/share/zoneinfo/Japan /etc/localtime

firewall-cmd --add-service=http --permanent
firewall-cmd --add-service=https --permanent
firewall-cmd --add-service=mysql --permanent

firewall-cmd --reload
```

### Apache:

```
yum -y install httpd openssl mod_ssl
sed -i -e 's/\#ServerName www.example.com:80/ServerNamelocal.example.com/g' /etc/httpd/conf/httpd.conf
systemctl enable httpd.service
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
CREATE USER "database_name"@"%" IDENTIFIED WITH mysql_native_password BY "XXXXXXXXXXX";
GRANT ALL PRIVILEGES ON *.* TO "database_name"@"%";
```

### PHP:
```
Install:
yum --enablerepo=remi,remi-php70  install php php-cli php-devel php-common php-fpm php-mbstring php-pgsql php-mysqli php-mcrypt php-apc php-opcache php-xml php-pecl-zip php-gd

Version confirm:
php -v
```

### VirtualHost sample:

#### http
```
<VirtualHost *:80>
  ServerName www.sample.jp
  DocumentRoot "/var/www/html/www.sample.jp/webroot"
  DirectoryIndex index.php index.html

  ErrorLog     logs/www.sample.jp_error_log
  CustomLog    logs/www.sample.jp_access_log combined_ssl
  LogLevel     warn

  <Directory "/var/www/html/www.sample.jp/webroot">
    Options         -Indexes +IncludesNoExec
    AllowOverride   all
    Require all granted
  </Directory>

<IfModule mod_deflate.c>
    SetOutputFilter DEFLATE

    BrowserMatch ^Mozilla/4\.0[678] no-gzip
    BrowserMatch ^Mozilla/4 gzip-only-text/html
    BrowserMatch \bMSI[E] !no-gzip !gzip-only-text/html

    SetEnvIfNoCase Request_URI \.(?:gif|jpe?g|png|ico)$ no-gzip dont-vary
    SetEnvIfNoCase Request_URI _\.utxt$ no-gzip

    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/atom_xml
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/x-httpd-php
</IfModule>
</VirtualHost>
```

#### https
```
<VirtualHost *:443>
  ServerName www.sample.jp
  DocumentRoot "/var/www/html/www.sample.jp/webroot"
  DirectoryIndex index.php index.html

  #Local用
  SSLEngine on
  SSLProtocol all -SSLv2
  SSLCipherSuite HIGH:MEDIUM:!aNULL:!MD5:!SEED:!IDEA
  SSLCertificateFile /etc/pki/tls/certs/localhost.crt
  SSLCertificateKeyFile /etc/pki/tls/private/localhost.key


  Header append Access-Control-Allow-Origin: "*"
  Header append Access-Control-Allow-Headers: x-requested-with


  AllowEncodedSlashes On
  ErrorLog     logs/www.sample.jp_ssl_error_log
  CustomLog    logs/www.sample.jp_ssl_access_log combined_ssl
  LogLevel     warn



  <Files ~ "\.(cgi|shtml|phtml|php3?)$">
    SSLOptions +StdEnvVars
  </Files>

  <Directory "/var/www/cgi-bin">
    SSLOptions +StdEnvVars
  </Directory>

  <Location ~ "\.(svn|git)">
    Deny from all
  </Location>

  <Directory "/var/www/html/www.sample.jp/webroot">
    Options         -Indexes +IncludesNoExec
    AllowOverride   all
    Require all granted
  </Directory>

  SetEnvIf User-Agent ".*MSIE.*" \
         nokeepalive ssl-unclean-shutdown \
         downgrade-1.0 force-response-1.0

  CustomLog logs/ssl_request_log \
          "%t %h %{SSL_PROTOCOL}x %{SSL_CIPHER}x \"%r\" %b"

<IfModule mod_deflate.c>
  CustomLog logs/ssl_request_log \
          "%t %h %{SSL_PROTOCOL}x %{SSL_CIPHER}x \"%r\" %b"
</IfModule>

<IfModule mod_deflate.c>
    SetOutputFilter DEFLATE

    BrowserMatch ^Mozilla/4\.0[678] no-gzip
    BrowserMatch ^Mozilla/4 gzip-only-text/html
    BrowserMatch \bMSI[E] !no-gzip !gzip-only-text/html

    SetEnvIfNoCase Request_URI \.(?:gif|jpe?g|png|ico)$ no-gzip dont-vary
    SetEnvIfNoCase Request_URI _\.utxt$ no-gzip

    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/atom_xml
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/x-httpd-php
</IfModule>

 <Location /phpmyadmin >
        ProxyPass http://127.0.0.1:
        ProxyPassReverse http://127.0.0.1:
  </Location>

</VirtualHost>
```
