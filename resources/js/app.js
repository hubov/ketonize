import './bootstrap';

import * as bootstrap from 'bootstrap'
import jQuery from 'jquery';
window.$ = jQuery;
window.bootstrap = bootstrap;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

export { jQuery, bootstrap };