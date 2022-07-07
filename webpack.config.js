const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // disc copy
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .copyFiles([
        {from: './assets/img', to: 'img/[path][name].[ext]'},
        {from: './assets/svg', to: 'svg/[path][name].[ext]'},
        {from: './assets/fonts', to: 'fonts/[path][name].[ext]'},
    ])
    // entries
    .addEntry('frontend', './assets/js/frontend.js')
    .addEntry('scroll_to_flash', './assets/js/scroll_to_flash.js')
    .addEntry('backend', './assets/js/backend.js')
    .addEntry('fullcalendardefaultsettings', './assets/js/fullcalendar.default-settings.js')
    .addEntry('fullcalendarstudentsettings', './assets/js/fullcalendar.student-settings.js')
    // config
    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
    // features
    // .autoProvidejQuery()
    .cleanupOutputBeforeBuild()
    .splitEntryChunks()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableStimulusBridge('./assets/controllers.json')
    .enableSingleRuntimeChunk()
    .enableSassLoader()
    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
