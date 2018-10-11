#!/bin/bash
# Update Peterjacksons script

php bin/magento maintenance:enable
git pull
composer install
(cd app/code/PandaGroup/StoreLocator && git remote set-url origin https://github.com/pandagrouppl/storelocator --push)
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy en_AU en_US --theme peterjacksons/petertheme --theme Magento/backend
(cd tools && npm install && gulp compile)
(cd wp/wp-content/themes/fishpig/ && chmod -R 775 .)
chown -R magento:www-data .
php bin/magento maintenance:disable
php bin/magento cache:flush
service varnish restart
