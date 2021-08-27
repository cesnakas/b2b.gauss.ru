let scroll = {
    run() {
        $('[data-scroll]').each((i,item) => {
            const $item = $(item);
            const link = $item.data('scroll');
            const $el = $(`[data-s-name=${link}]`);

            $item.on('click', () => this.scrollToElement($el))
        })
    },
    scrollToElement(element) {
        let self = this;
        if (!element.length) {
            return;
        }

        let wrapModal = element.parents('.b-modal');
        if (wrapModal && wrapModal.length) {
            return;
        }

        self.stateCheck = false;

        $('html, body').animate({
            scrollTop: element.offset().top - 110
        }, 700, () => {
            self.stateCheck = true;
        });
    }
};

module.exports = scroll;