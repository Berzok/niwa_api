/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// You can specify which plugins you need
import { Tooltip, Toast, Popover, Modal } from 'bootstrap';
import ky from 'ky';


// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/global.scss';
import './styles/layout.scss';
import './styles/index.scss';
import './styles/upload.scss';
import './styles/tabulator.scss';
// import './styles/themes/regalia.scss';
// import 'bootstrap/scss/bootstrap.scss';

// start the Stimulus application
import './bootstrap.js';
