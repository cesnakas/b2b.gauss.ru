let suggestions = {

    apiKey: window.isDev === true ? '2941cfcabdb3dd05ac1f54ebd1e68c3bbe78235a' : 'ba368733f1b6da93a8c1eb636a757236b36b8554',

    run($container) {
        let self = this;
        if (!$container) {
            $container = $('document');
        }

        $container.find('input[data-suggestion="address"]').each(function () {
            self.setAddresses($(this));
        });

        $container.find('input[data-suggestion="inn"]').each(function () {
            self.setInn($(this));
        });
    },


    setAddresses(searchInput) {
        let self = this;
        let div = $(searchInput).parent();
        let ul = div.find('ul[data-suggestion-ul]')['0'];
        if (!ul) {
            $(searchInput).after($( "<ul></ul>" ));
            div.find('ul').attr('data-suggestion-ul', true).addClass('b-form-suggest');
            ul = div.find('ul')['0'];
        }

        $(searchInput).on('keyup', (event) => {
            self.getRequestSuggestion('address', { query: event.target.value, count: 5, crossorigin: 'anonymous' })
                .then((items) => {
                    this.removeSearch(ul);

                    if(items.suggestions.length > 0) {
                        items.suggestions.map((item) => {
                            this.addSearchAddress(ul, item);
                        });
                    }

                })
                .catch(console.error);
        });
    },

    setInn(searchInput) {
        let self = this;
        let div = $(searchInput).parent();
        let ul = div.find('ul[data-suggestion-ul]')['0'];
        if (!ul) {
            $(searchInput).after($( "<ul></ul>" ));
            div.find('ul').attr('data-suggestion-ul', true).addClass('b-form-suggest');
            ul = div.find('ul')['0'];
        }

        $(searchInput).on('keyup', (event) => {
            self.getRequestSuggestion('party', { query: event.target.value, count: 5, crossorigin: 'anonymous' })
                .then((items) => {
                    this.removeSearch(ul);

                    if(items.suggestions.length > 0) {
                        items.suggestions.map((item) => {
                            this.addSearchInn(ul, item, event.target.value);
                        });
                    }

                })
                .catch(console.error);
        });
    },

    addSearchAddress (ul, item) {
        let li = document.createElement('li');
        li.setAttribute('class', 'b-form-suggest__item');
        let obLi = $(li);
        obLi.data('suggestion-item', item);

        li.onclick = function(element) {

            let list = ul.querySelectorAll('.b-form-suggest__item');
            let div = $(ul).parent();
            div.find('input').val(li.innerHTML);

            for (let index = 0; index < list.length; ++index) {
                ul.removeChild(list[index]);
            }

            let elData = $(element.target).data('suggestion-item');
            if (elData) {
                /**
                 * SPECIAL FOR ORDER CHECKOUT
                 * @type {string}
                 */
                let selectCity = elData.data.city;
                let selectKladr = elData.data.kladr_id;
                $('[data-location-kladr]').each(function () { // порядок важен
                    $(this).val(selectKladr);
                });
                $('[data-location-city]').each(function () {
                    let oldVal = $(this).val();
                    if (oldVal !== selectCity) {
                        $(this).val(selectCity);
                        $(this).trigger('change');
                    }
                });
            }

        };

        li.innerHTML = item.value;
        ul.appendChild(li);
    },

    addSearchInn (ul, item, curValue) {
        let li = document.createElement('li');
        li.setAttribute('class', 'b-form-suggest__item');
        let obLi = $(li);
        obLi.data('suggestion-item', item);

        let inputInn = item.data.inn ? item.data.inn : '';
        let inputName = item.data.name.short_with_opf ? item.data.name.short_with_opf : '';
        let inputAddress = item.data.address.value ? item.data.address.value : '';
        let inputPhone = item.data.phones ? item.data.phones : '';

        li.onclick = function(element) {

            let list = ul.querySelectorAll('.b-form-suggest__item');
            let div = $(ul).parent();curValue.toUpperCase();

            var regItems = /<span style.*>(.*)<\/span>(.*)/;
            var strInn = inputInn.match(regItems);

            if (strInn){
                inputInn = strInn[1] + strInn[2];
            }

            div.find('input').val(inputInn);

            for (let index = 0; index < list.length; ++index) {
                ul.removeChild(list[index]);
            }

            if (inputName) {
                $('input[data-suggestion="name"]').each(function () {
                    var regItems = /(.*)<span style.*>(.*)<\/span>(.*)/;
                    var strName = inputName.match(regItems);

                    if (strName){
                        inputName = strName[1] + strName[2] + strName[3];
                    }

                    $(this).val(inputName);
                    $(this).siblings('[data-f-label]').addClass('active');
                });
            }

            if (inputAddress) {
                $('input[data-suggestion="address"]').each(function () {
                    $(this).val(inputAddress);
                    $(this).siblings('[data-f-label]').addClass('active');
                });
            }

            if (inputPhone) {
                $('input[data-suggestion="phone"]').each(function () {
                    $(this).val(inputPhone);
                    $(this).siblings('[data-f-label]').addClass('active');
                });
            }

            Am.inputs.placeholder()
        };

        let posInn = inputInn.indexOf(curValue);
        let posName = inputName.indexOf(curValue.toUpperCase());

        if (posInn === 0){
            var re = curValue;
            var str = inputInn;
            var endStr = str.replace(re, '');

            var re = endStr;
            var str = inputInn;
            var startStr = str.replace(re, '');

            inputInn = '<span style="color:#FF0000">' + startStr + '</span>' + endStr;
        }

        if (posName !== -1){
            var subStr = inputName.replace(curValue, '');

            inputName = subStr.substr(0, posName) + '<span style="color:#FF0000">' +  curValue.toUpperCase() + '</span>' + subStr.substr(++posName);
        }

        if (inputName && inputInn) {
            li.innerHTML = inputName + '<br>' + inputInn;
        }

        ul.appendChild(li);
    },

    removeSearch (ul) {
        let list = ul.querySelectorAll('.b-form-suggest__item');

        for (let index = 0; index < list.length; ++index) {
            ul.removeChild(list[index]);
        }
    },


    getRequestSuggestion(api, params){
        let self = this;
        if (!params) {
            params = {};
        }
        if (!params.count) {
            params.count = 10;
        }

        return new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: 'https://suggestions.dadata.ru' + `/suggestions/api/4_1/rs/suggest/${api}`,
                data: JSON.stringify(params),
                dataType: "json",
                contentType: "application/json",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader ("Authorization", `Token ${self.apiKey}`);
                    xhr.setRequestHeader ("Content-Type", 'application/json');
                    xhr.setRequestHeader ("Accept", 'application/json');
                },
                success: function (data) {
                    resolve(data);
                },
                error: function (er) {
                    reject(er);
                }
            });
        });
    },


    initUsers(searchInput, users) {
        let self = this;
        let div = $(searchInput).parent();
        let ul = div.find('ul[data-suggestion-ul]')['0'];
        if (!ul) {
            $(searchInput).after($( "<ul></ul>" ));
            div.find('ul').attr('data-suggestion-ul', true).addClass('b-form-suggest');
            ul = div.find('ul')['0'];
        }

        $(ul).find('li').remove();

        if(users.length > 0) {
            users.map((item) => {
                self.addSearchUser(ul, item, searchInput);
            });
        } else {
            let emptyItem = {
                'ID':0,
                'FULL_NAME':'Нет результатов',
            };
            self.addSearchUser(ul, emptyItem, searchInput, true);
        }
    },


    addSearchUser (ul, item, searchInput, isEmpty=false) {
        let li = document.createElement('li');
        li.setAttribute('class', 'b-form-suggest__item');
        let obLi = $(li);
        obLi.data('suggestion-item', item);

        li.onclick = function(element) {
            let list = ul.querySelectorAll('.b-form-suggest__item');
            searchInput.val(li.innerHTML);

            if (isEmpty) {
                searchInput.val('');
            } else {
                searchInput.val(li.innerHTML);
            }

            for (let index = 0; index < list.length; ++index) {
                ul.removeChild(list[index]);
            }

            $('[data-search-user-id]').val(item.ID);  // clear local/templates/.default/components/citfact/lk.register.existinguser/.default/script.js
        };

        li.innerHTML = item.FULL_NAME;
        ul.appendChild(li);
    },
};

module.exports = suggestions;