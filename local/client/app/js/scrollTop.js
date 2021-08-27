let scrollTop = {
    run() {
        let $btn_up = $('.scroll-top');
        let fixedFromBot = 100;
        let mainPaddingBottom = 30;

        $(window).scroll(function() {

            if ($(window).scrollTop() > $(window).innerHeight()) {
                $btn_up.addClass('active');

                let offBottom = $('footer').offset().top - mainPaddingBottom;
                let btnOffBottom = $btn_up.offset().top + $btn_up.height();

                if(btnOffBottom > offBottom) {
                    $btn_up.css({
                        'bottom':mainPaddingBottom,
                        'position':'absolute'
                    });
                } else if (offBottom > $(window).scrollTop() + $(window).innerHeight() - fixedFromBot) {
                    $btn_up.css({
                        'bottom':fixedFromBot,
                        'position':'fixed'
                    })
                }
            } else {
                $btn_up.removeClass('active');
            }
        });
        
        $btn_up.on('click', function () {
            $('html, body').animate({scrollTop: 0},700);
            
            return false;
        });
    }
};

module.exports = scrollTop;