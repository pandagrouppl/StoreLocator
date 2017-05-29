#!/bin/bash
# Update Peterjacksons script

bin/magento setup:upgrade && bin/magento setup:di:compile && bin/magento setup:static-content:deploy
cd tools && npm install && gulp compile && cd ..
cd app/code/PandaGroup/StoreLocator/view/frontend/preact && npm install && npm run server && cd ../../../../../../..

