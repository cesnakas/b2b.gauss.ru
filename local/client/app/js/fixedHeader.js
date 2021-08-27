let fixedHeader = {
    run() {
        this.$header = $('.h--main');
        this.$headerFixed = this.$header.find('.h__fixed');
        if (this.$header.length > 0 && this.$headerFixed.length > 0) {
            this.setHeight();
            this.position = this.$header.offset().top;
            this.scroll();
            $(window).on('resize', () => this.setHeight());
            $(window).on('scroll', () => this.scroll());
        }
    },
    setHeight() {
        this.$header.height(this.$headerFixed.outerHeight());
    },
    scroll() {
        this.currentPosition = $(window).scrollTop();

        if (this.currentPosition > this.position) {
            this.$header.addClass('fixed');
        } else {
            this.$header.removeClass('fixed');
        }
    }
};

export default fixedHeader;