let tooltip = {
    run() {
        $('.tooltip:not(.init)').each(function() {
           new Tooltip($(this));
        });
    }
};

class Tooltip {
    constructor($item) {
        this.$item = $item;
        this.$icon = this.$item.find('.tooltip__icon');
        this.$text = this.$item.find('.tooltip__text');
        this.tooltipWidth = 230;

        this.handlers();

        this.init();
    }

    handlers() {
        this.$item.on('click', (e) => this.click(e));
        this.$item[0].addEventListener('mouseenter', () => this.show());
        this.$item[0].addEventListener('mouseleave', () => this.hide());
    }
    
    show() {
        this.$text.fadeIn(200);
        this.position();
    }
    
    hide()  {
        this.$text.stop();
        this.$text.fadeOut(200);
    }

    showIos()  {
        this.show();

        setTimeout(() => this.hide(), 3500);
    }

    click(e) {
        e.preventDefault();

        if($('.bx-ios').length && $(window).innerWidth() <= 1024)
            this.showIos();
    }
    
    position() {
        let positionLeft = this.$text.offset().left;
        
        let notEnoughSpace = () => {
            const tooltipRight = positionLeft + this.tooltipWidth;
            const $slider = this.$item.parents('[data-slider]');
            
            if($slider.length)
                return tooltipRight > window.innerWidth || tooltipRight > $slider.offset().left + $slider.innerWidth();
            else
                return tooltipRight > window.innerWidth
        };
    
        if(positionLeft < 0) { /* левый край */
            this.$text.offset({left: 15});
            this.$text.addClass('moved');
        } else if(notEnoughSpace()) { /* правый край */
            
            if($(window).innerWidth() > 767){ /* если десктоп - развернуть */
                this.$text.addClass('left');
            } else { /* подвинуть на мобилке */
                this.$text.offset({left: $(window).width() - this.tooltipWidth - 15});
                this.$text.addClass('moved');
            }
        } else {
            return false
        }
    }

    init() {
        this.$item.addClass('init');
    }
}

module.exports = tooltip;
