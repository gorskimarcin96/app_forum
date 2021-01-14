const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('main', './assets/js/main.js')
    .addEntry('post_add', './assets/js/post_add.js')
    .addEntry('post_page', './assets/js/post_page.js')
    .addEntry('post_post', './assets/js/post_post.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
    .autoProvidejQuery()
    .enableSassLoader()
    .enableVueLoader()
;

module.exports = Encore.getWebpackConfig();
