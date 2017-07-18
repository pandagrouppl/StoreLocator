#!/bin/bash
# Update Peterjacksons script

php bin/magento maintenance:enable
php bin/magento setup:static-content:deploy -t peterjacksons/petertheme
(cd tools && gulp compile)
chown -R magento:www-data .
php bin/magento cache:flush
service varnish restart
php bin/magento maintenance:disable
