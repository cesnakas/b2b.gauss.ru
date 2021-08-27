import validation from '../validation.js/validation.js';
import ajaxLoad from './ajax-load';
import inputs from '../../inputs';
import inputMask from '../../inputMask';

let formSubmit = {

    run() {
        this.formSubmitInit();
        this.changeSubmit();
        this.clickSubmit();
        this.hrefAjaxReload();
        this.changeSubmitParentForm();
    },

    changeSubmit() {
        $(document).on('change', '[data-auto-submit] form', function () {
            $(this).submit();
        })
    },

    changeSubmitParentForm() {
        $(document).on('change', '[data-change-submit-parent]', function () {
            $(this).closest('form').submit();
        })
    },

    clickSubmit() {
        $(document).on('click', '[data-click-submit]', function () {
            let $this = $(this);
            let target = $this.attr('data-click-submit');
            if (!target) {
                $this.closest('form').submit();
            } else {
                $('[data-click-submit-target=' + target + ']').submit();
            }
        })
    },

    hrefAjaxReload() {
        $(document).on('click', '[data-href-ajax-reload] a', function (e) {
            e.preventDefault();
            let $this = $(this);
            let container = $this.closest('[data-href-ajax-reload]');
            window['BX'].showWait();
            $.ajax({
                url: $this.attr('href'),
                type: 'POST',
                push: false,
                container: '[data-href-ajax-reload=' + container.attr('data-href-ajax-reload') + ']',
                timeout: 2000,
                scrollTo: false,
            }).done(function () {
                window['BX'].closeWait();
            });
        })
    },

    formSubmitInit() {
        let self = this;
        $(document).on('submit', '[data-form-submit] form', function (e) {
            e.preventDefault();
            let $this = $(this);
            self.formSubmit(
              'data-form-submit',
              $this.closest('[data-form-submit]').attr('data-form-submit'),
              $this.closest('[data-form-submit]').attr('data-form-callback')
          );
        });
    },

    formSubmit(containerDataName, containerDataValue, callback, params = {}) {
        if (!containerDataName) {
            return;
        }
        if (!containerDataValue) {
            containerDataValue = '""';
        }
        let containerSelector = '[' + containerDataName + '=' + containerDataValue + ']';
        let container = $(containerSelector);
        let form = $(container.find('form')[0]);

        if (params.skipSaving !== true && !validation.validate(form)) {
            form.find('[data-order-skip-saving]').val('Y');
            return;
        }
        let formData = new FormData(form[0]);
        formData.append(containerDataValue, 'Y');

        if (container.attr('data-form-submit-url')) {
            params.url = container.attr('data-form-submit-url');
        }
        
        ajaxLoad.load({
            container: containerSelector,
            data: formData,
            async: params.async,
            url: params.url,
            additionalContainers: params.additionalContainers,
            success: function (data) {
                inputs.placeholder();
                inputMask.run();
                window['BX'].closeWait();

                validation.scrollToMessage(container);
                if (typeof callback === 'function') {
                    callback(data);

                } else if(typeof callback === 'string') {
                    try {
                        eval(callback+'('+data+')');
                    } catch (e) {}
                }
            }
        });
    }
};


module.exports = formSubmit;
