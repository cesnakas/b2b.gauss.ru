let loader = {
    run() {
        if (window.bitrixLoader !== true) {
            this.showWaitInit();
            this.closeWaitInit();
        }
    },

    showWaitInit() {
        BX.showWait = function (node, msg) {
            let loader = $('[data-loader]');
            if (loader.length <= 0) {
                $('body').append('<div data-loader class="loader"></div>');
            }
        };


    },

    closeWaitInit() {
        BX.closeWait = function (node, obMsg) {
            $('[data-loader]').remove();
        };
    },
};

module.exports = loader;
