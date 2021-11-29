const mix = require('laravel-mix');
const path = require('path');

mix.ts('resources/assets/main.ts', 'public/assets/app.js')
    .vue({extractStyles: 'assets/app.css'})
    .sourceMaps(false, 'source-map')
    .disableNotifications()
    .version()
    .extract()
    .alias({
        '@': path.join(__dirname, 'resources/assets')
    });