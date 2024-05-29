#!/bin/sh
sed -i "s/TOREPLACE/${SERVER_NAME}/g" /etc/apache2/sites-available/000-default.conf
apt update
apt install php-mbstring -y
a2enmod rewrite
service apache2 restart

# Keep the container running
tail -f /dev/null
