import scrollbarWidth from  './_vendor/scrollbarWidth.js';
import inputMask from  './inputMask.js';
import inputs from  './inputs.js';
import showFileName from  './showFileName';
import toggleList from  './toggleList';
import counter from  './counter';
import datepicker from  './datepicker';

let modals = {
    run() {
        this.ajax();
        this.ajaxMarketing();
        this.video();
        this.image();
        this.close();

        $('[data-modal]').on('click', (e) => e.stopPropagation());
        $('[data-modal]').css('pointerEvents', 'all');
        /* разблокирование ссылок при инициализации */
    },

    reInit() {
        this.ajax();
        this.ajaxMarketing();
        this.video();
        // this.image();
        modals.run();
    },

    header: $('.h__top, .h__bottom, main'),

    scrollbar: scrollbarWidth.run(),

    thisPopup: null,

    callback: {
        open: function () {
            modals.thisPopup = $.magnificPopup.instance;

            $(document).on('mousedown', '.mfp-wrap', (e) => {
                if ($(e.target).is('.mfp-container, .mfp-content') && modals.thisPopup)
                    modals.thisPopup.close();
            });

            $('header, main').css('marginRight', modals.scrollbar * -1);

            if($('.bx-ie').length)
                return;

            modals.header.css('paddingRight', modals.scrollbar);
            modals.run();

        },
        close: function () {
            $('header, main').css('marginRight', 0);
            modals.header.css('paddingRight', '');
        },
        ajaxContentAdded: function () {
            inputMask.run();
            inputs.placeholder();
            // datepicker.run();
            showFileName.run();
            Ac.select.run();
            toggleList.run();
            counter.run();
            datepicker.run();
            modals.run();
        }
    },

    callbackMarketing: {
        elementParse: function () {
            let ids = [];

            $('input[type="checkbox"]:checked').each(function () {
                ids.push($(this).attr('data-id'));
            });

            let postData = {
                ids: ids
            };

            var mp = $.magnificPopup.instance;
            mp.st.ajax.settings.data = postData;
        },
        open: function () {
            $('header, main').css('marginRight', modals.scrollbar * -1);
            modals.header.css('paddingRight', modals.scrollbar);
            modals.thisPopup = $.magnificPopup.instance;

            $(document).on('mousedown', '.mfp-wrap', (e) => {
                if ($(e.target).is('.mfp-container, .mfp-content') && modals.thisPopup)
                    modals.thisPopup.close();
            });
        },
        close: function () {
            $('header, main').css('marginRight', 0);
            modals.header.css('paddingRight', '');
        },
        ajaxContentAdded: function () {
            inputMask.run();
            inputs.placeholder();
            // datepicker.run();
            showFileName.run();
            Ac.select.run();
            toggleList.run();
            counter.run();
            modals.run();
        }
    },

    close() {
        $(document).on('click', '[data-modal-close], [data-nav-mobile]', $.magnificPopup.close);
    },

    ajax($element = false) {
        let ajaxModals = $element || $('[data-modal="ajax"]');
        if (ajaxModals.length) {
            ajaxModals.magnificPopup({
                type: 'ajax',
                preloader: false,
                fixedBgPos: true,
                showCloseBtn: false,
                closeOnBgClick: false,
                removalDelay: 300,
                mainClass: "mfp-fade",
                callbacks: modals.callback
            });
        }
    },

    ajaxMarketing($element = false) {
        let ajaxModals = $element || $('[data-modal="marketing"]');
        if (ajaxModals.length) {

            ajaxModals.magnificPopup({
                type: 'ajax',
                ajax: {
                    settings: {
                        type: 'POST',
                        data: {}
                    }
                },
                preloader: false,
                fixedBgPos: true,
                showCloseBtn: false,
                closeOnBgClick: false,
                removalDelay: 300,
                mainClass: "mfp-fade",
                callbacks: modals.callbackMarketing
            });
        }
    },

    video() {
        let videoModals = $('[data-modal="video"]');
        if (videoModals.length) {
            videoModals.magnificPopup({
                type: 'iframe',
                removalDelay: 300,
                showCloseBtn: false,
                midClick: true,
                mainClass: "mfp-fade",
                callbacks: modals.callback
            });
        }
    },

    image() {
        let imageModals = $('[data-modal="image"]');
        if (imageModals.length) {
            imageModals.magnificPopup({
                type: 'image',
                fixedContentPos: true,
                fixedBgPos: true,
                overflowY: 'hidden',
                mainClass: "mfp-fade",
                showCloseBtn: false,
                removalDelay: 300,
                callbacks: modals.callback
            });
        }

        $('.b-media-zoom').addClass('init');
        /* добавление анимации при инициализации */
    },

    showDialog(url, afterOpenCallBack) {
        $.magnificPopup.open({
            items: {
                src: url,
                type: 'ajax'
            },
            preloader: true,
            showCloseBtn: false,
            mainClass: "mfp-fade",
            callbacks: {
                'ajaxContentAdded': function () {
                    Am.inputs.run();
                    Ac.select.run();
                    Am.inputMask.run();
                    Am.loader.run();
                    $( '.c__text' ).hide();
                    modals.run();

                    if (afterOpenCallBack && typeof afterOpenCallBack === 'function') {
                        afterOpenCallBack();
                    }
                }
            }
        });
    },

};

module.exports = modals;