const mix = require('laravel-mix');
const webpack = require('webpack');

const isProduction = mix.inProduction();

mix.js('resources/js/app.js', 'public/js')
   .js("resources/js/dashboard.js", "public/js")
   .js("resources/js/broadcastMessage.js", "public/js")
   .js("resources/js/account_show.js", "public/js")

   .setPublicPath('public')
//    .webpackConfig({
//       plugins: [
//           new webpack.DefinePlugin({
//               'process.env': {
//                   API_URL: JSON.stringify(isProduction ? '/app/Fetch' : '/LP_system/app/Fetch'),
//                   SYSTEM_URL: JSON.stringify(isProduction ? '/' : '/LP_system/'),
//               }
//           })
//       ]
//   });
