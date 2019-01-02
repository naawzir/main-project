const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .sourceMaps()
    .js('resources/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css');


mix.babel([
    'resources/js/lib/jquery.dataTables.min.js',
    'resources/js/add-agency-user.js',
    'resources/js/donut.js',
    'resources/js/global-helpers.js',
    'resources/js/tcp.js',
    'resources/js/user-edit-details.js',
    'resources/js/case/create.js',
    //'resources/js/case/case-listing.js',
    'resources/js/admin-datatables.js',
    'resources/js/admin-datatables-filters.js',
    'resources/js/userrole/index.js',
    'resources/js/notification.js',
    'resources/js/staff/performance.js',
], 'public/js/all.js');

mix
    .js([
        'node_modules/jquery-confirm/dist/jquery-confirm.min.js',
    ], 'public/js/jquery-confirm.min.js')
    .styles([
        'node_modules/jquery-confirm/dist/jquery-confirm.min.css',
    ], 'public/css/jquery-confirm.min.css');
