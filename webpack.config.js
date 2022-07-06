const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // disc copy
    .setOutputPath('public/build/')
    .setPublicPath('/build')
<<<<<<< ours
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
=======
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
>>>>>>> theirs
    .cleanupOutputBeforeBuild()
    .splitEntryChunks()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
<<<<<<< ours
    .enableStimulusBridge('./assets/controllers.json')
    .enableSingleRuntimeChunk()
    .enableSassLoader()
=======

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
>>>>>>> theirs
;

module.exports = Encore.getWebpackConfig();
