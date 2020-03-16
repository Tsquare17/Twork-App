const path = require('path');

const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

const UglifyJSPlugin = require('uglifyjs-webpack-plugin');

const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");

module.exports = {
    entry: ['./resources/assets/js/src/twork.js', './resources/assets/css/src/twork.scss'],
    output: {
        filename: './resources/assets/js/dist/twork.min.js',
        path: path.resolve(__dirname)
    },
    mode: 'development',
    devtool: 'cheap-eval-source-map',
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader",
                    options: {
                        presets: ['babel-preset-env']
                    }
                }
            },
            {
                test: /\.(sass|scss)$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader', 'sass-loader']
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: './resources/assets/css/dist/twork.min.css'
        }),
        new BrowserSyncPlugin({
            files: '**/*.php',
            host: 'localhost',
            port: 3000,
            proxy: 'http://localhost:8020/',
            injectChanges: true,
        }),
    ],
    optimization: {
        minimizer: [
            new UglifyJSPlugin({
                cache: true,
                parallel: true
            }),
            new OptimizeCSSAssetsPlugin({})
        ]
    }
};
