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

require(`${__dirname}/packages/core/src/resources/assets/mix.js`);
