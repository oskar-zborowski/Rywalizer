const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .addEntry('app', './assets/index.js')

    .setPublicPath('/build')
    .setOutputPath('public/build/')
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .splitEntryChunks()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableIntegrityHashes(Encore.isProduction())
    .enableSassLoader()
    .enableReactPreset()
    //.enableTypeScriptLoader()
    //.setManifestKeyPrefix('build/')

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
;

const config = Encore.getWebpackConfig();
config.target = ['web', 'es5']; // Transpile to ES5

module.exports = config;
