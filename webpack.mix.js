let mix = require('laravel-mix')

mix.setPublicPath('dist')
    .js('resources/js/field.js', 'js')
    .webpackConfig({
        resolve: {
            alias: {
                '@nova': path.resolve(
                    __dirname,
                    '../../laravel/nova/resources/js'
                )
            }
        }
    })
