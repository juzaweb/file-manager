let mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.disableNotifications();
mix.version();

mix.options(
    {
        postCss: [
            require('postcss-discard-comments') (
                {
                    removeAll: true
                }
            )
        ],
        uglify: {
            comments: false
        }
    }
);

mix.styles([
    'assets/vendors/bootstrap/css/bootstrap.min.css',
    'assets/vendors/font-awesome/css/font-awesome.min.css',
    'assets/css/mime-icons.min.css',
    'assets/css/cropper.min.css',
    'assets/css/dropzone.css',
    'assets/css/lfm.css',
], 'assets/public/css/filemanager.min.css');

mix.combine([
    'assets/vendors/jquery/jquery.min.js',
    'assets/vendors/popper.js/umd/popper.js',
    'assets/vendors/bootstrap/js/bootstrap.js',
    'assets/vendors/jquery-ui/jquery-ui.min.js',
    'assets/js/cropper.min.js',
    'assets/js/dropzone.js',
    'assets/js/script.js',
    'assets/js/handle.js',
], 'assets/public/js/filemanager.min.js');
