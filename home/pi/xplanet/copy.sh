#!/bin/sh

# TERRE
rm -f /var/www/img/xplanet_earth.png
cp /home/pi/xplanet/xplanet_earth.png /var/www/img/
cp /home/pi/xplanet/xplanet_earth.png /var/www/splash/img/
chown www-data:www-data /var/www/img/xplanet_earth.png
chown www-data:www-data /var/www/splash/img/xplanet_earth.png
