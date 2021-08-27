let stopPropagation = {
    run() {
        $('[data-link-stop-propagation]').on('click', function(e) {
            e.stopPropagation();
        });
    }
};

module.exports = stopPropagation;