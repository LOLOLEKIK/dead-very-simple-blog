#!/bin/sh
sed -i "s/TOREPLACE/${NAME_SERVER}/g" /etc/apache2/sites-available/000-default.conf
echo "SetEnv MYSQL_DATABASE ${MYSQL_DATABASE}" >> /etc/apache2/apache2.conf
echo "SetEnv MYSQL_USER ${MYSQL_USER}" >> /etc/apache2/apache2.conf
echo "SetEnv MYSQL_PASSWORD ${MYSQL_PASSWORD}" >> /etc/apache2/apache2.conf
echo "SetEnv NAME_SERVER ${NAME_SERVER}" >> /etc/apache2/apache2.conf
echo "SetEnv HTTPS ${HTTPS}" >> /etc/apache2/apache2.conf
echo "SetEnv MULTI_LANGUAGE ${MULTI_LANGUAGE}" >> /etc/apache2/apache2.conf
apt update
apt install php-mbstring -y
a2enmod rewrite
service apache2 restart
htpasswd -bc /etc/apache2/.htpasswd ${USERNAME_ADMIN_DASHBOARD} ${PASSWORD_ADMIN_DASHBOARD}

# Keep the container running
tail -f /dev/null
