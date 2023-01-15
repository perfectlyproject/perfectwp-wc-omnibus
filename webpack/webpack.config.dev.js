const Webpack = require('webpack');
const {merge} = require('webpack-merge');
const common = require('./webpack.common.js');

const WebpackBuildNotifierPlugin = require('webpack-build-notifier');

module.exports = env => {
  return merge(common(env), {
    mode: 'development',
    devtool: 'source-map',
    output: {
      filename: '[name].js',
    },
    plugins: [
      new WebpackBuildNotifierPlugin({
        sound: true,
      }),
      new Webpack.DefinePlugin({
        'process.env.NODE_ENV': JSON.stringify('development')
      })
    ],
    // optimization: {
    //   splitChunks: {
    //     // include all types of chunks
    //     chunks: 'all'
    //   }
    // }
  })
};