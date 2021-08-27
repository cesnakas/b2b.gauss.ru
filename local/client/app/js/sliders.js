import Swiper from 'swiper/dist/js/swiper.min.js';
import lazy from './lazy';

let sliders = {
    run() {
        this.sliders.forEach(function (item) {
            let nodeList = document.querySelectorAll(item.selector);
            for (let i = 0; i < nodeList.length; i++) {
                new Swiper(nodeList[i], item.options);
            }
        });

        /* вынести в ajax success */
        this.productSlider(1);
        this.productSlider(2);
        this.productSlider(3);

        this.mainSlider();
        this.mainSliderRight();
        this.detailSlider();
    },

    mainSlider() {
        let touchAutoplayTimeout = null;
        let touched = false;

        $('[data-slider-arrow-p="main"], [data-slider-arrow-n="main"]').on('click', () => touched = true);


        let mainSliderS = new Swiper(`[data-slider="main"]`, {

            spaceBetween: 20,
            slidesPerView: 3,
            slidesPerGroup: 3,
            loop: true,
            /*effect: 'fade',
            fadeEffect: {
                crossFade: true
            },*/
            pagination: {
                el: `.swiper-pagination--big`,
                dynamicBullets: true,
                clickable: true
            },
            resistanceRatio: 0,
            on: {
                'init': function () {
                    lazy.update();
                    this.el.addEventListener('mouseenter', () => {
                        this.autoplay.stop();
                    });

                    this.el.addEventListener('mouseleave', () => {
                        this.autoplay.start();
                    });
                },
                'transitionEnd': function () {
                    lazy.update();
                    objectFitImages();

                    if (touched) {
                        clearTimeout(touchAutoplayTimeout);
                        touchAutoplayTimeout = setTimeout(function () {
                            mainSliderS.autoplay.start();
                            touched = false;
                        }, 5000);
                    }
                },
                'touchEnd': function () {
                    touched = true;
                },
                'slideChangeTransitionEnd': function () {
                    Ac.lazy.update();
                }
            },
            navigation: {
                prevEl: '[data-slider-arrow-p="main"]',
                nextEl: '[data-slider-arrow-n="main"]',
            },
            breakpoints: {
                1278: {
                    slidesPerView: 2,
                    slidesPerGroup: 2
                },
                1022: {
                    slidesPerView: 1,
                    slidesPerGroup: 1,
                },
                767: {
                    slidesPerView: 2,
                    slidesPerGroup: 2,
                },
                500: {
                    slidesPerView: 1,
                    slidesPerGroup: 1
                },
            },
            autoplay: {
                delay: 5000,
            },
            disableOnInteraction: false,
        });

    },
    mainSliderRight() {
        let touchAutoplayTimeout = null;
        let touched = false;
        let self = this;

        $('[data-slider-arrow-p="main-right"], [data-slider-arrow-n="main-right"]').on('click', () => touched = true);

        let mainSliderS = new Swiper(`[data-slider="main-right"]`, {

            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            pagination: {
                el: `.swiper-pagination`,
                type: 'bullets',
                clickable: true,
            },
            resistanceRatio: 0,
            on: {
                'init': function () {
                    lazy.update();
                    self.offAutoplayYoutubeOnNoActiveSlides();
                },
                'transitionEnd': function () {
                    lazy.update();
                    objectFitImages();
                },
                'touchEnd': function () {
                    touched = true;
                }
            },
            disableOnInteraction: false,
        });

    },

    productSlider(id) {
        let touchAutoplayTimeout = null;
        let touched = false;

        const $item = $(`[data-slider="p-${id}"]`);

        if (!$item.length)
            return;

        const slideNum = $item.data('slides');
        let slidesNums = [];

        if (slideNum === 6)
            slidesNums = [6, 5, 4, 3];
        else if (slideNum === 5)
            slidesNums = [5, 4, 3, 2];
        else if (slideNum === 4)
            slidesNums = [4, 3, 3, 2];
        else
            slidesNums = [5, 4, 3, 2];

        let mainSliderS = new Swiper($item, {
            slidesPerView: slidesNums[0],
            spaceBetween: 10,
            on: {
                'init': function () {
                    lazy.update();
                    this.el.addEventListener('mouseenter', () => {
                        this.autoplay.stop();
                    });

                    this.el.addEventListener('mouseleave', () => {
                        this.autoplay.start();
                    });
                },
                'transitionEnd': function () {
                    lazy.update();
                    objectFitImages();

                    if (touched) {
                        clearTimeout(touchAutoplayTimeout);
                        touchAutoplayTimeout = setTimeout(function () {
                            mainSliderS.autoplay.start();
                            touched = false;
                        }, 3000);
                    }
                },
                'touchEnd': function () {
                    touched = true;
                }
            },
            navigation: {
                prevEl: `[data-slider-arrow-p="p-${id}"]`,
                nextEl: `[data-slider-arrow-n="p-${id}"]`,
            },
            simulateTouch: !$item.data('locked'),
            loop: true,
            autoplay: {
                delay: 3000,
            },
            breakpoints: {
                767: {
                    spaceBetween: 0, /* в стилях на псевдо */
                    slidesPerView: 'auto',
                },
                1023: {
                    slidesPerView: slidesNums[3],
                },
                1279: {
                    slidesPerView: slidesNums[2],
                },
                1799: {
                    slidesPerView: slidesNums[1],
                }
            }
        });
    },

    offAutoplayYoutubeOnNoActiveSlides() {
        let iframes = $('.swiper-slide-duplicate iframe');
        iframes.each(function (i, iframe) {
            let src = $(iframe).attr('src');
            if (src.search(/^https:\/\/www\.youtube\.com/ig) !== -1 ||
                src.search(/^http:\/\/www\.youtube\.com/ig) !== -1 ||
                src.search(/^www\.youtube\.com/ig) !== -1 ||
                src.search(/^youtube\.com/ig) !== -1) {
                if (src.search(/autoplay=1/ig) !== -1) {
                    src = src.replace(/autoplay=1/g, "autoplay=0");
                    $(iframe).attr('src', src);
                }
            }
        });
    },

    detailSlider() {
        let detailSliderThumbs = new Swiper('[data-slider="detailThumbs"]', {
            spaceBetween: 5,
            slidesPerView: 3,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            direction: 'vertical',
        });
        let detailSlider = new Swiper('[data-slider="detail"]', {
            spaceBetween: 0,
            slidesPerView: 1,
            simulateTouch: false,
            mousewheel: true,
            navigation: {
                prevEl: '[data-slider-a-prev="detail"]',
                nextEl: '[data-slider-a-next="detail"]',
                disabledClass: 'disabled'
            },
            pagination: {
                el: '[data-slider-p="detail"]',
                clickable: true
            },
            thumbs: {
                swiper: detailSliderThumbs
            },
        });
    },

    sliders: [
        {
            selector: '[data-slider="main-sale"]',
            options: {
                slidesPerView: 3,
                spaceBetween: 12,
                simulateTouch: false,
                on: {
                    'transitionEnd': function () {
                        lazy.update();
                    }
                },
                breakpoints: {
                    500: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    },
                    767: {
                        spaceBetween: 0, /* в стилях на псевдо */
                        slidesPerView: 'auto',
                    },
                    1023: {
                        slidesPerView: 1,
                    },
                    1799: {
                        slidesPerView: 2,
                    },
                }
            }
        },
    ]
};

module.exports = sliders;
