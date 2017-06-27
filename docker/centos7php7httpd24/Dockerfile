#
# Ssh + Apache + PHP
#
#   2017-05-11
#   CentOS 7 + epel,remi
#   Apache 2.4
#   PHP 7.0.X

FROM centos:7
MAINTAINER wang.yubo@dragon.jp

# update yum and install epel, remi
RUN yum update -y && \
yum install -y epel-release && \
yum install -y http://rpms.famillecollet.com/enterprise/remi-release-7.rpm && \
sed -i -e "s/enabled *= *1/enabled=0/g" /etc/yum.repos.d/epel.repo && \
sed -i -e "s/enabled *= *1/enabled=0/g" /etc/yum.repos.d/remi.repo && \

# install apache ssh postfix
yum install -y sudo which zip httpd httpd-tools openssh-server openssh-clients openssl mod_ssl cronie postfix && \

# install php supervisor
yum install --enablerepo=epel -y libmcrypt supervisor python-pip && \
yum install --enablerepo=remi -y gd-last && \
yum install --enablerepo=remi-php70 -y php php-devel php-gd php-mbstring php-mcrypt php-mysqlnd php-pear php-xml php-opcache php-intl && \

#clean cache
yum clean all --enablerepo=* && \

# initialize for ssh and create default login user
sed -i '/pam_loginuid\.so/s/required/optional/' /etc/pam.d/sshd && ssh-keygen -A && \
useradd -d /home/hoge -m -s /bin/bash hoge && echo hoge:hoge | chpasswd && echo 'hoge ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers && \

# config apache's ServerName
sed -i -e 's/\#ServerName www.example.com:80/ServerName local.example.com/g' /etc/httpd/conf/httpd.conf && \

#aws
pip install pip --upgrade && pip install awscli --user && \

# change os's timezone
cp -p /usr/share/zoneinfo/Japan /etc/localtime && \

# change php's timezone
sed -i -e "s/;date.timezone *=.*$/date.timezone = Asia\/Tokyo/" /etc/php.ini

ADD ./supervisord.conf /etc/supervisord.conf

ENV PATH="~/.local/bin:${PATH}"

EXPOSE 22 25 80 443

CMD ["/usr/bin/supervisord"]
