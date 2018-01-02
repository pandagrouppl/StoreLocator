#!/bin/bash
# Bundle js

mv pub/static/frontend/peterjacksons/petertheme/en_US pub/static/frontend/peterjacksons/petertheme/en_US_source
r.js -o build.js baseUrl=pub/static/frontend/peterjacksons/petertheme/en_US_source dir=pub/static/frontend/peterjacksons/petertheme/en_US
mv pub/static/frontend/peterjacksons/petertheme/en_AU pub/static/frontend/peterjacksons/petertheme/en_AU_source
r.js -o build.js baseUrl=pub/static/frontend/peterjacksons/petertheme/en_AU_source dir=pub/static/frontend/peterjacksons/petertheme/en_AU