let validation = {
    validExtension: [
        'image/jpeg',
        'image/png',
        'image/bmp',
        'application/excel',
        'application/vnd.ms-excel',
        'application/x-excel',
        'application/x-msexcel',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/pdf',
        'text/plain',
    ],
    validFileName: [
        'jpg',
        'jpeg',
        'png',
        'bmp',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'pdf',
    ],

    maxFileSize: 20000000,

    run() {
        this.agreeCheckBoxInit();
        this.agreeCheckBoxChange();
    },

    validate(form) {
        let self = this;
        let flag = true;
        let groupItemsWithErrors = {};

        form.find('[data-form-error]').addClass('hidden');
        form.find('[data-form-error-size]').addClass('hidden');
        form.find('[data-form-error-extension]').addClass('hidden');
        form.find('[data-form-success]').addClass('hidden');
        form.find('[data-form-auth-error]').addClass('hidden');

        form.find('input[type=text], input[type=radio], input[type=checkbox], textarea, input[type=password], select').each(function () {
            let itemFlag = true;
            let itemFlagFull = true;
            let itemFlagCorrect = true;
            let input = $(this);
            let optionValue;
            let fieldError;
            let val = (input.prop('nodeName') === 'SELECT' && !(optionValue = input.find('option:selected').attr('value'))) ? optionValue : input.val();
            let placeholder = input.attr('placeholder');
            let isEmail = input.attr('data-form-field-email') !== undefined;
            let isPhone = input.attr('data-form-field-phone') !== undefined;
            let isDate = input.attr('data-form-field-date') !== undefined;
            let formItem = input.closest('[data-f-item]');
            let formItemName = formItem.attr('data-f-item');

            if (formItem.length > 0) {
                fieldError = formItem.find('[data-form-error]');
            }
            if (val && isEmail) {
                val = val.split(' ').join('');
                input.val(val);
            }
            if (
                input.attr('data-required') === 'GROUP' &&
                (
                    input.attr('type') === 'radio' ||
                    input.attr('type') === 'checkbox'
                ) &&
                input.prop('checked') === false &&
                groupItemsWithErrors[formItemName] !== true
            ) {
                groupItemsWithErrors[formItemName] = input;
            } else if (
                input.attr('data-required') === 'GROUP' && (
                    input.attr('type') === 'radio' ||
                    input.attr('type') === 'checkbox'
                ) &&
                input.prop('checked') !== false
            ) {
                groupItemsWithErrors[formItemName] = true;
            } else if (
                input.attr('data-required') === 'Y' &&
                (
                    val === 'null' ||
                    val == null ||
                    val === undefined ||
                    val === '' ||
                    val === placeholder ||
                    val.length < 1 ||
                    val === '0' ||
                    val.trim() === ''
                )
            ) {
                itemFlag = false;
                itemFlagFull = false;
            } else {
                if (val && isPhone && !(
                    (/^\+7\s\(\d{3}\)\s\d{3}-\d{4}$/).test(val) || (/^\d{11}$/).test(val)
                )) {
                    itemFlag = false;
                    itemFlagCorrect = false;
                } else if (val && isEmail && !self.validEmailTest(val)) {
                    itemFlag = false;
                    itemFlagCorrect = false;
                } else if (val && isDate && !self.validDateTest(val)) {
                    itemFlag = false;
                    itemFlagCorrect = false;
                }
            }

            if (itemFlag) {

                formItem.removeClass('error');

            } else if (!itemFlagFull) {
                flag = false;
                formItem.addClass('error');
                fieldError.html("Поле не заполнено");

                if($('.order').length)
                    self.scrollToMessage(form);

            }else if(!itemFlagCorrect){
                flag = false;
                formItem.addClass('error');
                fieldError.html("Поле заполнено некорректно");

                if($('.order').length)
                    self.scrollToMessage(form);
            }

            if (input.attr('data-pass') && input.val()) {
                let inputConfirmPass = form.find('[data-confirm-pass]');
                if (input.val() !== inputConfirmPass.val()) {
                    input.addClass('error');
                    formItem.addClass('b-input--error');
                    fieldError.removeClass('hidden');
                    flag = false;
                } else {
                    input.removeClass('error');
                    formItem.removeClass('b-input--error');
                    fieldError.addClass('hidden');
                }
            }
        });

        for (let key in groupItemsWithErrors) {
            if (groupItemsWithErrors.hasOwnProperty(key) && groupItemsWithErrors[key] !== true) {
                let item = groupItemsWithErrors[key];
                item.addClass('error');
                let formItem = item.closest('[data-f-item]');
                let fieldError = formItem.find('[data-form-error]');
                formItem.addClass('error');
                fieldError.removeClass('hidden');
                fieldError.html("Не выбрано значение");
                flag = false;
            }
        }

        flag = this.checkFileField(form) && flag;
        /*if (!flag) {
            self.scrollToMessage(form);
        }*/

        return flag;
    },

    scrollToMessage(container) {
        let isModal = (container.closest('.b-modal').length > 0);
        let error = container.find('.b-input--error:first:not(.hidden), [data-required].error, .b-form__item.error');
        let success = container.find('.form--success:first:not(.hidden)');
        if (!isModal && (error.length > 0 || success.length > 0)) {
            let pos = 0;
            let posHeight = 0;

            if (error.length > 0) {
                pos = error.offset();
                posHeight = error.height();
            } else {
                pos = success.offset();
            }

            $('html,body').animate({scrollTop: pos.top - 100 - posHeight}, 1000);
        }
    },

    checkFileField(form) {
        let self = this;
        let $file = form.find('input[type=file]');
        let flagRequire = true;
        let flagValidSize = true;
        let flagValidExtension = true;

        if ($file.length === 0) {
            return true;
        }

        $file.each(function () {
            let $this = $(this);
            let container = $this.closest('[data-file-field]');
            let required = $this.attr('data-file-field-required');
            let errorsBlock = container.find('[data-field-errors]');
            let file = $this[0].files[0];

            let errors = [];

            if (container.hasClass('hidden')) {
                return true;
            }

            if (required !== undefined) {
                if (!file) {
                    errors.push('Не выбран файл');
                    flagRequire = false;
                }
            }

            if (file) {
                if (file.size > self.maxFileSize) {
                    flagValidSize = false;
                    errors.push('Превышен допустимый размер файла в 20МБ');
                }

                if (
                    self.validFileName.indexOf(self.getExtension(file.name)) === -1
                ) {
                    flagValidExtension = false;
                    errors.push('Неверный формат файла');
                }
            }

            if (errors.length > 0) {

                container.addClass('error');
                let errorsHtml = errors.reduce((html, error) => {
                    return html + '<span class="b-form__text' +
                      (flagValidExtension ? '' : ' b-form__text--extension') +
                      (flagValidSize ? '' : ' b-form__text--size') + '">' + error + '</span>';
                }, '');

                $(errorsBlock).html(errorsHtml);

            } else {

                container.removeClass('error');
                $(errorsBlock).html('');

            }
            
        });

        return flagRequire && flagValidSize && flagValidExtension;
    },

    getExtension(filename) {
        let parts = filename.split('.');
        return parts[parts.length - 1];
    },

    validEmailTest(mail) {
        let re_email = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re_email.test(mail);
    },

    // Validates that the input string is a valid date formatted as "dd.mm.yyyy"
    validDateTest(dateString) {
        // First check for the pattern
        if(!/^\d{1,2}\.\d{1,2}\.\d{4}$/.test(dateString))
            return false;

        // Parse the date parts to integers
        let parts = dateString.split(".");
        let day = parseInt(parts[0], 10);
        let month = parseInt(parts[1], 10);
        let year = parseInt(parts[2], 10);

        let inputDate = new Date(year, month, day);

        let dateNow = new Date();

        if (dateNow.getHours() < 14) {
            dateNow.setDate(dateNow.getDate() + 1);
        } else {
            dateNow.setDate(dateNow.getDate() + 2);
        }

        let deliveryDay = dateNow.getDate();
        let deliveryMonth = dateNow.getMonth() + 1;
        let deliveryYear = dateNow.getFullYear();

        let deliveryDate = new Date(deliveryYear, deliveryMonth, deliveryDay);

        if (deliveryDate > inputDate) {
            return false;
        }

        var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

        // Adjust for leap years
        if(year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
            monthLength[1] = 29;

        // Check the range of the day
        return day > 0 && day <= monthLength[month - 1];
    },

    agreeCheckBoxInit() {
        let self = this;
        $('[data-agree-check-box]').each(function () {
            let $this = $(this);
            self.processAgreeCheckBox($this)
        });
    },

    agreeCheckBoxChange() {
        let self = this;
        $(document).on('change', '[data-agree-check-box]', function () {
            let $this = $(this);
            self.processAgreeCheckBox($this)
        });
    },

    processAgreeCheckBox($this) {
        let name = $this.attr('data-agree-check-box');
        if ($this.prop('checked')) {
            $('[data-agree-submit=' + name + ']').prop('disabled', false).removeClass('btn__disabled');
        } else {
            $('[data-agree-submit=' + name + ']').prop('disabled', true).addClass('btn__disabled');
        }
    },
};

module.exports = validation;
