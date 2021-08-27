import scrollbarWidth from  '../_vendor/scrollbarWidth.js';

let desktopMenu = {
    run () {
        
        if(!window.innerWidth > 1279)
            return;
        
        const $btn = $('.h-menu__btn');
        
        const $menu = $('.h-menu-d');
        const $img = $('.h-menu-d__img');
        
        const scrollW = scrollbarWidth.run();

        let $headerWrapper;
        if ($('.h__fixed').length > 0) {
            $headerWrapper = $('.h__fixed');
        } else {
            $headerWrapper = $('.h');
        }

        $menu.css({
            'top': $headerWrapper.outerHeight(),
            'paddingRight': scrollW,
            'height': $(window).outerHeight() - $headerWrapper.outerHeight()
        });
    
        $img.css({
            'right': scrollW * -1
        });
        
        let open = false;

        $btn.on('click', function () {
            $menu.toggle();
            $btn.toggleClass('active');
            $btn.find('.btn-nav').toggleClass('active');
            $('html').toggleClass('locked');
            
            if(!open)
                $('.h__top, .h__bottom, .main').css('paddingRight', scrollW);
            else
                $('.h__top, .h__bottom, .main').css('paddingRight', '');
            
            open = !open;
        });
        
        let delay = 0;
    
        $('.h-menu-d__links > a').each(function (i) {
            delay = i;
            $(this).css('animationDelay', `.${i}s`);
        });
        
        $('.h-menu-d__socials').css('animationDelay', `.${delay + 2}s`);
    },
};

module.exports = desktopMenu;

