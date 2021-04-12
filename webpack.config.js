var Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    .copyFiles([
        {from: './assets/img', to: 'img/[path][name].[ext]'},
        {from: './assets/svg', to: 'svg/[path][name].[ext]'},
        {from: './assets/fonts', to: 'fonts/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/', to: 'ckeditor4/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false},
        {from: './node_modules/ckeditor4/adapters', to: 'ckeditor4/adapters/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/lang', to: 'ckeditor4/lang/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/plugins', to: 'ckeditor4/plugins/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/skins', to: 'ckeditor4/skins/[path][name].[ext]'},
        {from: './node_modules/google-charts/dist', to: 'google-charts/[path][name].[ext]'}
    ])
    // public path used by the web server to access the output path
    .setPublicPath('/build')

    /*
     * ENTRY CONFIG
     */
    .addEntry('frontend', './assets/js/frontend.js')
    .addEntry('backend', './assets/js/backend.js')
    .addEntry('fullcalendardefaultsettings', './assets/js/fullcalendar.default-settings.js')
    .addEntry('fullcalendarstudentsettings', './assets/js/fullcalendar.student-settings.js')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables LESS support
    .enableLessLoader()

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
