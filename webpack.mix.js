const mix = require('laravel-mix');

/**
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// frontend
mix.js('Modules/Cms/resources/js/app.js', 'public/theme/cms/js')
    .sass('Modules/Cms/resources/sass/app.scss', 'public/theme/cms/css', {
        // Rewrite CSS urls for app.scss
        processUrls: false,
    });


// backend
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .js('resources/js/backend.js', 'public/theme/infyom/js')
    .sass('resources/sass/backend.scss', 'public/theme/infyom/css')
    .sass(
        'Modules/Common/resources/sass/custom-tinymce.scss',
        'public/js/tinymce/css'
    )
    .copyDirectory('vendor/tinymce/tinymce', 'public/js/tinymce')
    .copyDirectory('Modules/Common/resources/js/tinymce', 'public/js/tinymce');

// tools-filemanager
mix.js(
    'Modules/Tools/resources/assets/js/filemanager.js',
    'public/theme/tools/js'
).sass(
    'Modules/Tools/resources/assets/sass/filemanager.scss',
    'public/theme/tools/css'
);
