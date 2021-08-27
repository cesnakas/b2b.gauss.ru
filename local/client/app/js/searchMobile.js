/* переключение активности строки поиска на мобилке */
let searchMobile = {
    run() {
        if(window.innerWidth < 1280)
            this.init();
    },
    
    init() {
        this.$btn = $('[data-search-btn]');
        this.$wrap = $('[data-search-wrap]');
        this.$input = this.$wrap.find('input');
        let opened = false;
    
        this.$btn.on('click', (e)=>{
            if(!this.$input.val())
                e.preventDefault();
            
            this.$wrap.toggleClass('active');
            
            opened = !opened;
    
            if(opened)
                this.$input.focus();
    
            $('.h__m .h__inner > *:not(.h__search-m)').animate({
                width: 'toggle',
                padding: 'toggle',
                margin: 'toggle'
            }, opened ? 400 : 0);
        })
    }
};

module.exports = searchMobile;