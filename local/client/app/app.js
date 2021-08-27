let __svg__ = {path: '../icons/*.svg', name: 'sprite.svg'};
// Load plugins
import 'normalize.css';
import 'magnific-popup';
import 'daterangepicker/daterangepicker';
import 'air-datepicker';
import _ from 'underscore';
///Load polyfills
import './js/polyfills';

// Load styles
import 'swiper/dist/css/swiper.min.css';

import 'magnific-popup/dist/magnific-popup.css';
import 'nouislider/distribute/nouislider.min.css';
import 'air-datepicker/dist/css/datepicker.min.css';
import 'daterangepicker/daterangepicker.css';
import './styles/imports';

// load modules
import sliders from './js/sliders';
import inputMask from './js/inputMask';
import modals from './js/modals';
import counter from './js/counter';
import rangeSlider from './js/rangeSlider';
import tooltip from './js/tooltip';
import inputs from './js/inputs';
import scroll from './js/scroll';
import scrollTop from './js/scrollTop';
import fixedHeader from "./js/fixedHeader";
import showMore from './js/showMore';
import toggleList from './js/toggleList';
import animation from './js/animation';
import searchMobile from './js/searchMobile';
import blockFixOnScroll from './js/blockFixOnScroll';
import tabs from './js/tabs';
import desktopMenu from './js/menu/desktopMenu';
import chart from './js/chart';
import showFileName from './js/showFileName';
import cookie from './js/cookie';
import register from './js/register';

import loader from './js/loader';
import subscribe from './js/subscribe';
import formSubmit from './js/citfact.lib/form.submit.js/form-submit';
import validation from './js/citfact.lib/validation.js/validation';
import CatalogActions from './js/catalogActions';
import FastOrder from './js/src/fast-order';
import datepicker from './js/datepicker';
import UploadableBasket from './js/uploadbasket';
import editorTableMobile from './js/editorTableMobile';

import suggestions from './js/suggestions';
import checkboxAgree from './js/checkboxAgree';

import {PriceListKP} from './js/src/priceList';
import order from './js/order';
import showPassword from './js/showPassword';
import User from './js/user';

import follower from './js/follower';
import newsText from './js/newsText';
import stopPropagation from './js/stopPropagation';
import dropDragInputFile from './js/dropDragInputFile';
import select2 from './js/select2';

import managerLk from './js/manager_lk';
import catalogTable from './js/catalogTable';
import WOW from '../node_modules/wow.js/dist/wow.js'
import checkForm from './js/checkForm';

///Loader init
loader.run();

///Composite
if (
  typeof window.frameCacheVars !== 'undefined'
  && BX
) {
    BX.addCustomEvent('onFrameDataReceived', () => {
        modals.run();
        inputs.run();
        inputMask.run();
    });
} else {
    document.addEventListener('DOMContentLoaded', () => {
        modals.run();
        inputs.run();
        inputMask.run();
    });
}

// Create the event App.Ready
var eventAppReady = document.createEvent('Event');
var eventAppLibReady = document.createEvent('Event');
eventAppReady.initEvent('App.Ready', true, true);
eventAppLibReady.initEvent('AppLib.Ready', true, true);

// init modules
$(document).ready(() => {
    document.dispatchEvent(eventAppLibReady);
    new WOW().init();
    sliders.run();
    inputMask.run();
    modals.run();
    counter.run();
    rangeSlider.run();
    tooltip.run();
    inputs.run();
    scroll.run();
    // scrollTop.run(); TODO вернуть
    fixedHeader.run();
    showMore.run();
    toggleList.run();
    animation.run();
    searchMobile.run();
    blockFixOnScroll.run();
    tabs.run();
    desktopMenu.run();
    register.run();
    newsText.run();
    formSubmit.run();
    validation.run();
    cookie.run();
    select2.run();

    chart.run();
    showFileName.run();

    subscribe.run();
    CatalogActions.run();
    FastOrder.run();
    datepicker.run();

    new UploadableBasket();
    checkboxAgree.run();
    editorTableMobile.run();

    new PriceListKP();

    showPassword.run();

    follower.run();

    stopPropagation.run();
    catalogTable.run();
    checkForm.run();

    document.dispatchEvent(eventAppReady);
});


// Export components
exports.modals = modals;
exports.sliders = sliders;
exports.inputMask = inputMask;
exports.inputs = inputs;
exports.chart = chart; 
exports.validation = validation;
exports.toggleList = toggleList;
exports.datepicker = datepicker;
exports.suggestions = suggestions;
exports.tooltip = tooltip;
exports.CatalogActions = CatalogActions;
exports.register = register;
exports.loader = loader;
exports.dropDragInputFile = dropDragInputFile;
exports.order = order;
exports.counter = counter;
exports.user = new User();
exports.managerLk = managerLk;
exports.catalogTable = catalogTable;
exports.checkForm = checkForm;
