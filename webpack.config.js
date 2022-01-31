const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .copyFiles([
        {from: './assets/img', to: 'img/[path][name].[ext]'},
        {from: './assets/svg', to: 'svg/[path][name].[ext]'},
        {from: './assets/fonts', to: 'fonts/[path][name].[ext]'},
    ])
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
    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables LESS support
    .enableSassLoader()

    // enables Stimulus support
    .enableStimulusBridge('./assets/controllers.json')

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
