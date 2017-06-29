var webpack = require('webpack');
var path = require('path');
var config = require('./base.config');

config.output.path = path.join(__dirname, './../../../../../../../../pub/static/frontend/peterjacksons/petertheme/en_US/PandaGroup_StoreLocator/dist');

module.exports = config;

