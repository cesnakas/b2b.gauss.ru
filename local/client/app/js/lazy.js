import Blazy from 'blazy';

let lazy = {
    run() {
        this.init();
        window.addEventListener('resize', this.init);
    },

    init() {
        this.isDelayInit = false;
        let delay;
        let initDelay = () => {
            if(this.isDelayInit) return;

            clearTimeout(delay);
            delay = setTimeout(this.delayedImages, 1000);
        };

        if(!$('[data-src]').length)
            Ac.lazy.delayedImages(); 

        this.bLazy = new Blazy({
            src: 'data-src',
            selector: '.lazy',
            successClass: 'init',
            offset: 100,
            success: initDelay,
            breakpoints: [
                {
                    width: 767,
                    src: 'data-src-mobile',
                }
            ]
        });
    },
    delayedImages() {
        this.isDelayInit = true;

        if($(window).innerWidth() < 1280)
            return;

        const $item = $('[data-delayed]');

        $item.each((i, item) => {
            const $item = $(item);

            $item.attr('src', $item.data('delayed'))
                .removeAttr('data-delayed');

            $item.on('load', this.update);
        })
    },
    update() {
        if(this.bLazy)
            this.bLazy.revalidate();
        objectFitImages();
    },
    interval: null,
    ajaxLoad() {
        clearInterval(this.interval);
        this.interval = setInterval(() => {

            if($('.blocks__inner .lazy:not(.init)').length)
                this.update();
            else
                clearInterval(this.interval)

        }, 100)
    }
};

module.exports = lazy;
