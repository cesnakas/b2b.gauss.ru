if (window.ProfileChange === undefined) {
    var ProfileChange = {
        inited: false,


        init: function(params) {
            this.inited = true;
            this.params = params;
            var _this = this;

            // Форма редактирования полей профиля
            $(document).on('submit', _this.params.formSelector, function(e) {
                e.preventDefault();
                BX.showWait();
                var form = $(this);
                if ($(form).prop('id') === 'lk_change_profile' && Am.validation.validate(form)) {
                    var data = new FormData(this);

                    var request = BX.ajax.runComponentAction('citfact:main.profile', 'profileChange', {
                        mode: 'class',
                        data: data,
                        signedParameters: _this.params.signedParameters
                    });
                    request.then(function (response) {
                        var regItems = /<div class="b-tabs__item active".*>([\s\S]*.*)<\/div>[\s]*<\/div>[\s]*<\/form>/;
                        var items = response['data']['RESPONSE']['html'].match(regItems);
                        $(".b-tabs__item").replaceWith(items[1]);
                        BX.closeWait();
                        Am.inputs.run();
                        Ac.select.run();
                        Am.inputMask.run();
                        Am.tooltip.run();
                        Am.validation.run();
                    }).catch(function (e) {
                        BX.closeWait();
                    });
                }else{
                    BX.closeWait();
                }
            });

            // Форма отправки договора на email
            $(document).on('submit', _this.params.sendContractFormSelector, function(e) {
                e.preventDefault();
                var form = $(this);

                if ($(form).prop('id') === 'send_contract_to_email_form') {
                    if (Am.Validation.validateForm(form).length === 0) {

                        window['BX'].showWait();

                        let errorContainer = form.find(".errors_cont");
                        let successContainer = form.find(".result_cont");
                        let data = form.serialize();
                        let siteId = form.data('site-id');

                        $.ajax({
                            type: "POST",
                            url: '/local/include/modals/send_contract_to_email.php?site=' + siteId,
                            data: data,
                            dataType: "json",
                            success: function (response) {
                                if (response.success) {
                                    successContainer.html(response.msg);
                                    form[0].reset();
                                } else {
                                    errorContainer.html(response.msg);
                                }
                            },
                            complete: function () {
                                BX.closeWait();
                                Am.inputs.run();
                                Ac.select.run();
                                Am.inputMask.run();
                                Am.tooltip.run();
                                Am.validation.run();
                            }
                        });
                    }
                }
            });

        },
    };
}