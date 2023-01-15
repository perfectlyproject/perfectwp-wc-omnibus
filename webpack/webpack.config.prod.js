const Webpack = require('webpack');
const {merge} = require('webpack-merge');
const common = require('./webpack.common.js');
const TerserPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');

module.exports = env => {
  return merge(common(env), {
    mode: 'production',
    output: {
      filename: '[name].[hash].js'
    },
    devtool: false,
    plugins: [
      new CleanWebpackPlugin({
        cleanOnceBeforeBuildPatterns: ['**/*', '!fonts/*', '!img/*']
      }),
      new Webpack.DefinePlugin({
        'process.env.NODE_ENV': JSON.stringify('production')
      }),
    ],
    optimization: {
      minimizer: [new TerserPlugin({
        parallel: true,
        cache: true,
        extractComments: true,
        terserOptions: {
          ecma: 5,
          ie8: false,
          compress: true,
          warnings: true,
        },
      }), new OptimizeCSSAssetsPlugin({

      })]
    }
  });
};