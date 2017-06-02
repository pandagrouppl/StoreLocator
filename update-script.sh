#!/bin/bash
# Update Peterjacksons script

php bin/magento maintenance:enable
cd app/code/PandaGroup/StoreLocator && git remote set-url origin https://github.com/pandagrouppl/storelocator --push && cd ../../../..
php bin/magento setup:upgrade && php bin/magento setup:di:compile && php bin/magento setup:static-content:deploy && php bin/magento cache:flush
cd tools && npm install && gulp compile && cd ..
cd app/code/PandaGroup/StoreLocator/view/frontend/preact && npm install && npm run server && cd ../../../../../../..
php bin/magento maintenance:disable


