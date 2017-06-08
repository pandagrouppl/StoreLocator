#!/bin/bash
# Update Peterjacksons script

php bin/magento maintenance:enable
OWNER=$(stat -c '%U' index.php)
GROUP=$(stat -c '%G' index.php)
cd app/code/PandaGroup/StoreLocator && git remote set-url origin https://github.com/pandagrouppl/storelocator --push && cd ../../../..
php bin/magento setup:upgrade && php bin/magento setup:di:compile && php bin/magento setup:static-content:deploy && php bin/magento cache:flush
cd tools && npm install && gulp compile && cd ..
cd app/code/PandaGroup/StoreLocator/view/frontend/preact && npm install && npm run server && cd ../../../../../../..
chown -R $OWNER:$GROUP .
php bin/magento maintenance:disable


