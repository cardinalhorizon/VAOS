const { mix } = require('laravel-mix');


mix.setPublicPath('../../public');
mix.js(__dirname + '/Resources/assets/js/app.js', 'js/materialcrew.js')
    .sass( __dirname + '/Resources/assets/sass/materialize.scss', 'css/materialcrew.css');
