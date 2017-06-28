var webpack = require('webpack');
var path = require('path');
var config = require('./base.config');

config.output.path = path.join(__dirname, './../../web/dist');

module.exports = config;

