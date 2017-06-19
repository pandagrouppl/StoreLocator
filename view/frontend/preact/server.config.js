var webpack = require('webpack');
var path = require('path');

module.exports = {
    devtool: 'source-map',
    entry: {
        js: './src/index.js',
        vendor: ['whatwg-fetch', 'preact']
    },
    output: {
        path: path.join(__dirname, './../web/dist'),
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
                    'babel-loader',
                    'eslint-loader'
                ]
            }
        ]
    },
    resolve: {
        alias: {
            'react': 'preact-compat',
            'react-dom': 'preact-compat'
        },
        extensions: ['.webpack-loader.js', '.web-loader.js', '.loader.js', '.js', '.jsx'],
        modules: [
            path.resolve(__dirname, 'node_modules')
        ]
    },
    plugins: [
        new webpack.ProvidePlugin({
            Promise: 'es6-promise-promise'
        })
    ]
};

