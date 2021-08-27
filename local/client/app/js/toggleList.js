let toggleList = {
    run() {
        this.default();
        $(window).resize(this.default);


        this.filter();
        this.orderIe();
        this.closeBlock();
    },
    default() {
        let $allBtns = '[data-toggle-btn-m], [data-toggle-btn]';
        let $btn = window.innerWidth < 768 ? $allBtns : '[data-toggle-btn]';

        $(document).off('click.toggle');
        $(document).on('click.toggle', $btn, function (e) {
            e.preventDefault();
            
            let $this = $(this);
            let $wrap = $this.parents('[data-toggle-wrap]:first');
            
            $wrap.toggleClass("active");
            $this.toggleClass("active");
            $wrap.find('[data-toggle-list]:first').slideToggle();
            
            Ac.lazy.update();
            Ac.select.run();
        });
    },
    filter() {
        const $btn = $('[data-filter-btn]');
        const $filter = $('[data-filter]');
        const $mask = $('[data-f-mask]');

        $filter.show();

        if($(window).width() < 768)

        $btn.on('click', toggle);
        $mask.on('click', toggle);
        
        function toggle() {
            $filter.toggleClass('active');
            $mask.fadeToggle();
        }
    },

    orderIe() {
        if($('.order').length && $('.bx-ie').length)
            $(document).on('click', '.b-checkbox__label:not(.active)', function () {
                $('.b-checkbox__label').removeClass('active');
                $(this).addClass('active');
                $(this).find('input[type="radio"]').click();
            })
    },
    closeBlock() {
        $(document).on('click', '[data-block-close]', function () {
            $('[data-block-modal]').hide();
        })
    }
};

module.exports = toggleList;