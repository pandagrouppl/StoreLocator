#!/bin/bash
# Install Peterjacksons script

curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.2/install.sh | bash
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"

if [[ $EUID = 0 ]]; then
    nvm install node
    npm install -g gulp-cli
else
    sudo nvm install node
    sudo npm install -g gulp-cli
fi

find var vendor pub/static pub/media app/etc -type f -exec chmod u+w {} \; && sudo find var vendor pub/static pub/media app/etc -type d -exec chmod u+w {} \; && sudo chmod u+x bin/magento
