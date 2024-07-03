const path = require('path');
const webpack = require('webpack');
const TerserPlugin = require("terser-webpack-plugin");
const JavaScriptObfuscator = require('webpack-obfuscator');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const ParallelUglifyPlugin = require('webpack-parallel-uglify-plugin');
const CompressionWebpackPlugin = require('compression-webpack-plugin');

module.exports = {
    mode: "production",
    entry: path.resolve(__dirname, '/src/main.js'),
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'dist'),
        chunkFilename: 'chunk.[chunkhash].js',
        clean: true,
    },
    module: {
        rules: [
            {
                test: /\.(sa|sc|c)ss$/i,
                use: [{ loader: MiniCssExtractPlugin.loader }, 'css-loader', 'sass-loader'],
            },
            {
                test: /\.less$/i,
                use: [
                    { loader: MiniCssExtractPlugin.loader },
                    "css-loader",
                    "less-loader",
                ],
            },
            {
                test: /\.js$/,
                use: [
                    'babel-loader'
                ],
                exclude: /(node_modules|bower_components)/
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                type: 'asset/resource',
                generator: {
                    filename: '../font/[name][ext]'
                }
            },
        ],
    },
    optimization: {
        minimize: true,
        minimizer: [
            new TerserPlugin({
                parallel: true
            }),
            new CssMinimizerPlugin(),
        ],
        // splitChunks: {
        //     name: 'runtime',
        //     chunks: 'all',
        // },
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "[name].css",
            chunkFilename: 'chunk.[chunkhash].css'
        }),
        new webpack.BannerPlugin({
            banner: "Lvshujun@PuockNG"
        }),
        new ParallelUglifyPlugin({
            uglifyJS: {
                output: {
                    beautify: false,
                    comments: false
                },
                compress: {
                    drop_console: false,
                    collapse_vars: true,
                    reduce_vars: true
                }
            }
        }),
    ]
};