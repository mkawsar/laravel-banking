let mix = require('laravel-mix');

let system_env = require('dotenv').config();
let app_env = system_env.parsed.APP_ENV;

mix.js('resources/assets/js/app.js', 'public/js')
    .styles([
        'resources/assets/css/themify-icons.css'
    ], 'public/css/themify-icons.css')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .copyDirectory('resources/assets/fonts', 'public/fonts');


if (mix.inProduction()) {
    mix.version();

    mix.extract([
        'vue',
        'vue-router',
        'vuex',
        'vue-style-loader'
    ]);
}

mix.webpackConfig({
    plugins: [
        // new BundleAnalyzerPlugin()
    ],
    resolve: {
        extensions: ['.js', '.json', '.vue'],
        alias: {
            '~': path.join(__dirname, './resources/assets/js')
        }
    },
    output: {
        publicPath: "/",
        chunkFilename: (app_env === 'production') ? 'js/[name].[chunkhash].js' : 'js/[name].js'
    }
});
