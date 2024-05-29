#!/bin/sh
sed -i "s/TOREPLACE/${SERVER_NAME}/g" /etc/apache2/sites-available/000-default.conf
echo "SetEnv MYSQL_DATABASE ${MYSQL_DATABASE}" >> /etc/apache2/apache2.conf
echo "SetEnv MYSQL_USER ${MYSQL_USER}" >> /etc/apache2/apache2.conf
echo "SetEnv MYSQL_PASSWORD ${MYSQL_PASSWORD}" >> /etc/apache2/apache2.conf
apt update
apt install php-mbstring -y
a2enmod rewrite
service apache2 restart

# Keep the container running
tail -f /dev/null
