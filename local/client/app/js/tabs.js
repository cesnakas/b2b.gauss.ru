import inputs from './inputs';
import select from './select';
// import showFileName from  './showFileName';

let tabs = {
    run() {
        this.default();
    },
    default() {
        $(document).on('click', '[data-tab-btn]', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const $this = $(e.target);
            const dataLink = $this.data('tab-btn');
            const $item = $(`[data-tab-body="${dataLink}"]`);
        
            if($this.hasClass('active')) { /* клик по уже активному табу */
                return;
            }
        
            const $wrap = $this.parents('[data-tab-group]:first');
            const $header = $this.parents('[data-tab-header]:first');
            const $links = $header.find('[data-tab-btn]');
            const $items = $wrap.find('[data-tab-content]:first > [data-tab-body]');
            const $extends = $('[data-tab-body-extend]');
        
            $links.removeClass('active');
            $items.removeClass('active');
            if ($extends.is(`[data-tab-body="${dataLink}"]`)) {
                $extends.slideDown();
            }
            else $extends.slideUp();
            $this.addClass('active');
            $item.addClass('active');

            pluginsUpdate();
            
            function pluginsUpdate() {
                Ac.lazy.update();
                inputs.placeholder();
                select.default();
                // showFileName.run();
            }
        })
    },
};

module.exports = tabs;
