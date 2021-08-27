let checkForm = {
    run() {
        if($('.f-form form').length>0){
            var form = $('.f-form form');
            var inputs = form.find('[data-required="Y"]');
            this.checkForm();
            inputs.on('keyup change', this.checkForm);
        }
    },

    checkForm() {
        var form = $('.f-form form');
        var inputs = form.find('[data-required="Y"]');
        var button = form.find('button[type=submit]');
        button.attr('disabled', 'disabled');

        var empty = false;

        inputs.each(function() {
            if ($(this).val() === '') {
                empty = true;
            }
        });

        if (empty) {
            button.attr('disabled', 'disabled');
        } else {
            button.removeAttr('disabled');
        }
    }

};

module.exports = checkForm;