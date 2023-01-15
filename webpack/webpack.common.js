const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const WpAssets = require('./lib/assets');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const StylelintPlugin = require('stylelint-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');
const config = {
  paths: {
    src: path.resolve(__dirname, '../assets'),
    dist: path.resolve(__dirname, '../public')
  },
};

module.exports = env => {
  let fileNames = env.NODE_ENV === 'production' ? '[name].[hash].[ext]' : '[name].[ext]';

  return {
    entry: {
      admin: [
        './assets/admin/js/app.ts',
        './assets/admin/scss/app.scss',
      ],
    },
    output: {
      path: config.paths.dist,
      filename: '[name].js',
    },
    plugins: [
      new webpack.ProgressPlugin(),
      // new CopyWebpackPlugin({
      //   patterns: [
      //     {from: './assets/admin/img', to: 'admin/img'},
      //   ]
      // }),
      new MiniCssExtractPlugin({
        filename: env.NODE_ENV === 'production' ? '[id].[hash].css' : '[name].css'
      }),
      new StylelintPlugin(),
      new WpAssets(),
      new ESLintPlugin(),
      new webpack.ProvidePlugin({
        $: 'jquery',
        jQuery: 'jquery'
      })
    ],
    resolve: {
      extensions: ['.js', '.ts', '.tsx'],
    },
    module: {
      rules: [
        {
          test: /\.(tsx?|jsx?)$/,
          exclude: /node_modules\/(?!(dom7|swiper)\/).*/,
          use: [
            {
              loader: 'babel-loader'
            },
          ]
        }, {
          test: /\.(ttf|eot|woff(2)?)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
          use: {
            loader: 'file-loader',
            options: {
              name: fileNames,
              outputPath: 'fonts/',
            },
          },
        }, {
          test: /\.(gif|png|jpe?g|svg)$/,
          use: [
            {
              loader: 'file-loader',
              options: {
                name: fileNames,
                outputPath: 'img/',
              },
            },
            // {
            //   loader: 'image-webpack-loader',
            //   options: {
            //     mozjpeg: {
            //       enabled: true,
            //       progressive: true,
            //       quality: 70,
            //     },
            //     optipng: {
            //       enabled: false,
            //     },
            //     pngquant: {
            //       quality: [0.65, 0.90],
            //       speed: 4,
            //     },
            //     gifsicle: {
            //       interlaced: false,
            //     },
            //   },
            // },
          ],
        }, {
          test: /\.(sa|sc|c)ss$/,
          use: [
            MiniCssExtractPlugin.loader,
            'css-loader',
            {
              loader: 'postcss-loader',
              options: {
                postcssOptions: {
                  plugins: [
                    require('autoprefixer')({
                      grid: 'autoplace'
                    }),
                  ],
                }
              },
            }, {
              loader: 'sass-loader',
            }],
        },
      ],
    },
  }
};
