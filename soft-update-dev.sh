#!/bin/bash
# Update Peterjacksons script
git pull
composer install
php bin/magento maintenance:enable
php bin/magento setup:static-content:deploy en_AU -t peterjacksons/petertheme
(cd tools && gulp compile)
chown -R magento:www-data .
php bin/magento cache:flush
service varnish restart
php bin/magento maintenance:disable
