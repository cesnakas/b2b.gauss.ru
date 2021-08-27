let showPassword = {
    run() {
        $(document).on('click', '[data-show-password]', function() {
            const $btn = $(this);
            const $input = $btn.parent().find('input');
            $input.attr('type', $input.attr('type') === 'password' ? 'text' : 'password');
            $btn.toggleClass('active');
        })
    },
};

module.exports = showPassword;
