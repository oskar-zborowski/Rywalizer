const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const path = require('path');

module.exports = {
    mode: 'development',
    stats: 'minimal',
    entry: {
        app: path.resolve(__dirname, 'resources/assets/main.tsx')
    },
    output: {
        path: path.resolve(__dirname, 'public/assets'),
        filename: '[name].[hash].js',
        publicPath: 'assets/'
    },
    optimization: {
        removeAvailableModules: false,
        removeEmptyChunks: false,
        splitChunks: false
    },
    devtool: 'source-map',
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    { loader: 'css-modules-typescript-loader' },
                    {
                        loader: 'css-loader',
                        options: {
                            modules: {
                                localIdentName: '[name]_[local]__[hash:base64:5]'
                            }
                        }
                    },
                    { loader: 'sass-loader' },
                ]
            },
            {
                test: /\.css$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    { loader: 'css-loader' },
                ]
            },
            {
                test: /\.(woff2?|ttf|otf|eot|png)$/,
                exclude: /node_modules/,
                loader: 'file-loader',
                options: {
                    name: '[name].[ext]'
                }
            },
            {
                test: /\.svg$/,
                use: [
                    'babel-loader',
                    { loader: 'svg-inline-loader' }
                ]
            },
            {
                test: /\.tsx?$/,
                use: [
                    {
                        loader: 'ts-loader',
                        options: {
                            transpileOnly: true,
                            experimentalWatchApi: true,
                        },
                    },
                ],
                exclude: /node_modules/,
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: 'app.[hash].css',
        }),
        new WebpackManifestPlugin({
            basePath: '/assets/',
            fileName: path.resolve(__dirname, 'public/mix-manifest.json')
        }),
        new CleanWebpackPlugin()
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/assets')
        },
        extensions: ['.js', '.ts', '.jsx', '.tsx', '.css', '.json', '.scss']
    }
};