let showMore = {
    run() {
        this.$btn = $('[data-show-more-btn]');
        this.$content = $('[data-show-more]');
        this.stringNum = 15;
        this.lineHeight = this.$content.children().css('line-height') === 0 ? this.$content.css('line-height') : this.$content.children().css('line-height');
        this.minHeight = parseInt(this.lineHeight) * this.stringNum;
        this.maxHeight = this.$content.innerHeight();
        
        this.initHeight();
    },
    
    initHeight() {

        if (this.$content.is('.active')) {
            this.$content.css('height',this.maxHeight);
        }

        else {
            if(this.maxHeight > this.minHeight) {
                this.$content.addClass('small');
                this.$content.css('height',this.minHeight);

                this.$btn.css('display','inline-flex');
                this.toggle();
            } else  {
                this.$btn.css('display','none');
            }
        }
    },
    
    changeHeight() {
        const newHeight = this.$content.hasClass('active') ? this.minHeight : this.maxHeight;
        
        this.$content.animate({'height': newHeight}).toggleClass('active');
    },
    
    toggle() {
        this.$btn.on('click', () => {
            this.$btn.toggleClass('active');
            
            this.changeHeight();
        })
    },

    open() {
        this.$btn.addClass('active');
        this.$content.animate({'height': this.maxHeight}).addClass('active');
    },

    close() {
        this.$btn.removeClass('active');
        this.$content.animate({'height': this.minHeight}).removeClass('active');
    }
};

module.exports = showMore;