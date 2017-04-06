var webpack = require('webpack');
var path = require('path');

module.exports = {
    entry: {
        js: './src/index.js',
        vendor: ['preact']
    },
    output: {
        path: path.join(__dirname, './../../../../../../../pub/static/frontend/peterjacksons/petertheme/en_US/Light4website_StoreLocator/dist'),
        filename: '[name].js',
        libraryTarget: 'amd',
        library: 'store-locator'
    },
    module: {
        rules: [
            {
                test: /\.css$/,
                exclude: /node_modules/,
                use: [
                    'style-loader',
                    'css-loader'
                ]
            },
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: [
                    'babel-loader'
                ],
            },
        ],
    },
    resolve: {
        extensions: ['.webpack-loader.js', '.web-loader.js', '.loader.js', '.js', '.jsx'],
        modules: [
            path.resolve(__dirname, 'node_modules')
        ]
    }
};

