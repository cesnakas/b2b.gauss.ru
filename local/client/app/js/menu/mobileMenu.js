let mobileMenu = {
    run () {
        
        if(window.innerWidth > 1279)
            return;
        
        const $html = $('html');
        let isOpen = false;
        let zMenu = 0;
        let lockedTimeout;
        let windowPosition;
    
        /* отображение меню */
    
        $('[data-m-menu-btn]').on('click', (e) => {
            const $btn = $(e.target);
            const $menu = $(`[data-m-menu='${$btn.data('m-menu-btn')}']`);
            $menu.show();

            if(zMenu === 0)
                zMenu = $menu.css('zIndex');
            else
                $menu.css('zIndex', ++zMenu);
            
            if(!isOpen) /* если меню открывается - запомнить позицию при открытии */
                windowPosition = $(window).scrollTop();
            
            /* добавление активности на кнопку переключатель */
            $btn.toggleClass('active');
            
            /* отображение меню */
            $menu.toggleClass('active');
    
            isOpen = $('.m-menu').hasClass('active');
            
            /* если меню открывается - заблокировать html после открытия (0.8s) время анимации в css  */
            if(isOpen) {
                lockedTimeout = setTimeout(function () {
                    $html.addClass('locked')
                }, 800);
            }
            /* иначе отменить блокировку html, снять класс, если она уже есть, проскроллить к позиции открытия */
            else {
                clearTimeout(lockedTimeout);
                $html.removeClass('locked');
                $(window).scrollTop(windowPosition);
                zMenu = 19;
            }
        });
    },
};

module.exports = mobileMenu;

