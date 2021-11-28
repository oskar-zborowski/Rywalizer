const mix = require('laravel-mix');

mix.ts('resources/assets/main.ts', 'public/assets/app.js')
    .vue({extractStyles: 'assets/app.css'})
    .sourceMaps(false, 'source-map')
    .disableNotifications()
    .version()
    .extract();