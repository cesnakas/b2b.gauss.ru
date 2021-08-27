/* фиксация блоков при скролле в пределах родительского контейнера */
let blockFixOnScroll = {
    run() {
        const $item = $('[data-fix-item]:visible');
        
        if(!$item.length || window.innerWidth < 1024)
            return;
        
        this.pos = $item.offset().top;
        
        this.scroll();
        $(window).scroll(this.scroll);
    },
    scroll() {
        let $sidebar = $('[data-fix-sidebar]');
        let $item = $sidebar.find('[data-fix-item]');
        // let headerMargin = $item.data('fix-item') || 0;
        let headerMargin = parseInt($('.main').css('paddingTop'));
        
        let posStop = $sidebar.offset().top + $sidebar.innerHeight() - $item.innerHeight() - headerMargin;
        
        /* факсация в конце род. блока */
        if($(window).scrollTop() > posStop) {
            $sidebar.addClass('active')
        }
        /* факсация к шапке */
        else if($(window).scrollTop() > blockFixOnScroll.pos - headerMargin) {
            $item.addClass('scroll').css({
                'top': headerMargin
            });
            $sidebar.removeClass('active')
        } else {
            clearFix()
        }
        
        function clearFix() {
            $item.removeClass('scroll');
            $item.css({
                'top':'0'
            });
            $sidebar.removeClass('active');
        }
    },
};

module.exports = blockFixOnScroll;