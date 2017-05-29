#!/bin/bash
# Install Peterjacksons script

curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.2/install.sh | bash
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"

if [[ $EUID > 0 ]]; then
    nvm install node
    npm install -g gulp-cli
else
    sudo nvm install node
    sudo npm install -g gulp-cli
fi
cd bin && ./magento setup:upgrade && ./magento setup:di:compile && ./magento setup:static-content:deploy && cd ..
cd tools && npm install && gulp compile && cd ..
cd app/code/PandaGroup/StoreLocator/view/frontend/preact && npm install && npm run server && cd ../../../../../../..