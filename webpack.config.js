const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')
    .copyFiles([
        {
            from: './assets/images/logos/',
            to: 'images/logos/[name].[ext]',
        },
        {
            from: './assets/images/home/',
            to: 'images/home/[name].[ext]',
        },
        {
            from: './assets/images/location/',
            to: 'images/location/[name].[ext]',
        },
        {
            from: './assets/images/policy/',
            to: 'images/policy/[name].[ext]',
        },
        {
            from: './assets/images/about/',
            to: 'images/about/[name].[ext]',
        },
        {
            from: './assets/fonts/',
            to: 'fonts/[name].[ext]',
        }
    ])

    /** CKEditor**/
    .copyFiles([
        {
            from: './public/bundles/fosckeditor/',
            to: 'ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false
        },
        {
            from: './public/bundles/fosckeditor/adapters',
            to: 'ckeditor/adapters/[path][name].[ext]'
        },
        {
            from: './public/bundles/fosckeditor/lang',
            to: 'ckeditor/lang/[path][name].[ext]'
        },
        {
            from: './public/bundles/fosckeditor/plugins',
            to: 'ckeditor/plugins/[path][name].[ext]'
        },
        {
            from: './public/bundles/fosckeditor/skins',
            to: 'ckeditor/skins/[path][name].[ext]'
        },
        {
            from: './public/bundles/fosckeditor/vendor',
            to: 'ckeditor/vendor/[path][name].[ext]'
        }])

    /** FMElfinder**/
    .copyFiles([
        {
            from: './public/bundles/fmelfinder/css',
            to: 'fmelfinder/css/[path][name].[ext]'
        },
        {
            from: './public/bundles/fmelfinder/img',
            to: 'fmelfinder/img/[path][name].[ext]'
        },
        {
            from: './public/bundles/fmelfinder/js',
            to: 'fmelfinder/js/[path][name].[ext]'
        },
        {
            from: './public/bundles/fmelfinder/sounds',
            to: 'fmelfinder/sounds/[path][name].[ext]'
        }])

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/app.js')

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
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    .enableSassLoader()
    .enablePostCssLoader()

    .autoProvideVariables({
        Cookies: 'js-cookie',
        L: 'leaflet',
        bodyScrollLock : 'body-scroll-lock/lib/bodyScrollLock.min.js'
    })

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
