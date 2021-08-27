import 'select2/dist/js/select2';

let select2 = {
    run() {
        $(document).ready(function() {
            $('.js-select-search').select2({
                language: {
                    noResults: function (params) {
                        return "Нет результатов";
                    }
                }
            });
        });
    }
};

module.exports = select2;