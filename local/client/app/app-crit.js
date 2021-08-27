// Load plugins
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import svg4everybody from 'svg4everybody';
window.svg4everybody = svg4everybody;

import objectFitImages from 'object-fit-images';
window.objectFitImages = objectFitImages;

import PerfectScrollbar from 'perfect-scrollbar';
window.PerfectScrollbar = PerfectScrollbar;

// Load styles
import './styles/imports-crit.js';

// Load modules
import svgUse from './js/svgUse';
import lazy from './js/lazy';
import select from './js/select';
import mobileMenu from './js/menu/mobileMenu';

// Init modules
objectFitImages();
svgUse.run();
select.run();
mobileMenu.run();

// Export components
exports.select = select;
exports.lazy = lazy;

$(document).ready(() => {
    lazy.run();
    $.getScript(`/local/client/build/m.js?${window.jsFileTime}`);
});