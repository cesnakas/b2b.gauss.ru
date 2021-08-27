let datepicker = {

    setMinDate: true,
    dateStamp: new Date(),

    run () {

        if (undefined !== window.educationMonthOffset) {
            let date = new Date();
            date.setMonth(date.getMonth() + window.educationMonthOffset);
            this.dateStamp = date;
        }

        this.default(this.dateStamp);
    },

    default(date) {

        $('[data-datepicker] > input').each(function () {
            const $this = $(this);

            let params = {
                autoClose: true,
                dateFormat: 'dd.mm.yyyy',
                onSelect: function(formattedDate, date, inst) {
                    $this.trigger('input');
                }
            };

            if (false !== window.setMinDateForDatePicker) {
                params.minDate = date;
            }

            $this.datepicker(params);

        });

        $('[data-datepicker-delivery] > input').each(function () {
            const $this = $(this);

            let params = {
                autoClose: true,
                dateFormat: 'dd.mm.yyyy',
                onSelect: function(formattedDate, date, inst) {
                    $this.trigger('input');
                }
            };

            if (false !== window.setMinDateForDatePicker) {

                let date = new Date();

                if (date.getHours() < 14) {
                    date.setDate(date.getDate() + 1);
                    params.minDate = date;
                } else {
                    date.setDate(date.getDate() + 2);
                    params.minDate = date;
                }
            }

            $this.datepicker(params);

        });
    },
};

module.exports = datepicker;

