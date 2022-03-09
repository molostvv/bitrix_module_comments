const path = require('path');
//const { VueLoaderPlugin } = require('vue-loader')

module.exports = {
  entry: {
      script: './index.js'
  },
  output: {
    path: path.resolve(__dirname, '../'),
    filename: 'script.js'
  },
  resolve: {
    extensions: ['.vue', '.js'],
    alias: {
      'vue$': 'vue/dist/vue.esm.js',
    }
  },
  module: {
    rules: [
       { test: /\.vue$/, loader: 'vue-loader' },
       { test: /\.(js)$/, use: 'babel-loader' },
       { test: /\.css$/, use: [ 'vue-style-loader', 'css-loader']}
    ]
  },
 // plugins: [new VueLoaderPlugin()],
  stats: {
    errorDetails: false,
  },
 // mode: 'development'
}