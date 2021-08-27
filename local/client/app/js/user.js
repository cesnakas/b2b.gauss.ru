'use strict';

export default class User {

    constructor() {
        this.initListeners();
    }

    initListeners() {

    }

    initDeactivation() {
        $('[data-form-delete-user]').on('submit', (event) => {
            event.preventDefault();
            let formData = {};

            $.each($(event.currentTarget).serializeArray(), (key, element) => {
                formData[element.name] = element.value;
            });

            if ($.isEmptyObject(formData) === false) {
                let data = {
                    'isAjaxAction': 'Y',
                    'action': 'deactivateUser',
                    'user': formData
                };

                $.ajax({
                    type: 'POST',
                    url: window.location.href,
                    data: data,
                    timeout: 3000,
                    success: () => {
                        location.href = window.location.href;
                    }
                });
            }
        });
    }
}