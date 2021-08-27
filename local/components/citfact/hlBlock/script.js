if (window.HlBlock === undefined) {
    var HlBlock = {
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
                var inputs = [];

                if ($(form).prop('id') === 'lk_change_addresses' && Am.validation.validate(form)) {
                    var data = new FormData(this);
                    var htmlContainerSelector = _this.params.htmlContainerSelector;

                    var request = BX.ajax.runComponentAction('citfact:hlBlock', 'hlBlock', {
                        mode: 'class',
                        data: data,
                        signedParameters: _this.params.signedParameters
                    });
                    request.then(function (response) {

                        $('.b-form__item[data-new="Y"]').each(function(){

                            $(this).find('input').prop("disabled", true);
                            $(this).find('.plus').remove();
                            inputs.push($(this));
                        });

                        var regItems = /<div class="b-tabs__item active".*>([\s\S]*.*)<\/div>[\s]*<\/div>[\s]*<\/form>/;
                        var items = response['data']['RESPONSE']['html'].match(regItems);
                        $(".b-tabs__item").replaceWith(items[1]);
                        $(".form__text").before(inputs);
                        Am.inputs.placeholder();
                        BX.closeWait();
                    }).catch(function (e) {
                        BX.closeWait();
                    });
                }else{
                    BX.closeWait();
                }
            });
        },
    };
}