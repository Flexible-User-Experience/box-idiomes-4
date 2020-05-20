// any CSS you import will output into a single css file (app.css in this case)
import '../css/backend.less';

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import PDFJS from 'pdfjs-dist';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// require jQuery normally
//const $ = require('jquery');

// create global $ and jQuery variables
//global.$ = global.jQuery = $;
global.Routing = global.Routing = Routing;
global.PDFJS = global.PDFJS = PDFJS;

console.log('Hello Backend Webpack Encore! Edit me in assets/js/app.js');
