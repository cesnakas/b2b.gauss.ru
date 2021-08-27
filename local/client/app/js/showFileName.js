let showFileName = {
    run() {
        let $inputs = "[data-file-upload]";

        if($($inputs).length) {
            let dataFileText = $("[data-file-upload='add']").next('[data-file-text]')[0].innerHTML;
            this.removeFile(dataFileText);
            jQuery.fn.outerHTML = function(s) {
                return s
                    ? this.before(s).remove()
                    : jQuery("<p>").append(this.eq(0).clone()).html();
            };
            let $addField = $("[data-file-upload='add']").parents('.b-form__upload').outerHTML();

            $(document).on('change', $inputs, function() {
                let $container = $(this).closest('[data-file-field]').find('.b-form__upload');
                let fileName = this.value.match(/[^\/\\]+$/);
                let $filenameContainer = $(this).siblings('[data-file-text]');
                let flagValue = false;
                let flagCount = false;
                let count = 0;
                let $btnDelete = $container.find('#btn-del');

                if (fileName) {
                    $filenameContainer.text(fileName[0]);
                    $container.addClass('active');
                    if ($btnDelete.length > 0) {
                        $btnDelete.removeClass('hidden');
                    }
                    $container.find('[data-file-load]').hide();

                    $('[data-file-upload]').each(function(i,elem) {

                        flagValue = this.value.match(/[^\/\\]+$/) ? true : false;
                        count++;
                    });

                    flagCount = count < 1 ? true : false;

                    let nameVal = Number($('[data-file-upload]:last').attr('name').match(/form_file_(.*)/)[1]) + 1 ;

                    $addField = $addField.replace(/(name="form_file_)([^"]*)/, "$1"+nameVal+"");
                    $addField = $addField.replace(/(data-file-field-required="Y")/, '');

                    if ($(this).data('fileUpload') === 'add' && flagValue && flagCount) {
                        $(this).parents('.b-form__upload').after($addField);
                    }
                } else {
                    $filenameContainer.html(dataFileText);
                    $container.removeClass('active');
                    if ($btnDelete.length > 0) {
                        $btnDelete.addClass('hidden');
                    }
                    $container.find('[data-file-load]').show();
                }
            })
        }
    },

    removeFile(dataFileText) {
        let $el = $('[data-btn-delete]');
        if ($el.length > 0) {
            $el.each(function (ind, item) {
                $(item).on('click', function () {
                    let $container = $(item).closest('[data-file-field]');
                    if ($container.hasClass('error')) {
                        $container.removeClass('error');
                    }
                    $container.find('[data-file-text]').html(dataFileText);
                    $container.find('.b-form__upload').removeClass('active');
                    $(item).addClass('hidden');
                    $container.find('[type=file]').val('');
                    $container.find('[data-file-load]').show();
                });
            })
        }
    }
};



module.exports = showFileName;
