import selectric from 'selectric';
import order from './order';

let select = {
    run() {
        this.default();
        this.catalogSort();
        this.orderSort();
        this.personal();
        this.inputsOrders();

   //     this.reg();
    },

    default() {
        $('select:visible:not(.hidden)').not('.js-select-search').each(function () {
            const $this = $(this);
            let $isWhite = $this.is('.select--white');
            let $wrap;
            let $filter;
            let $item;
            let $items;
            let $scroll;

            $this.selectric({
                customClass: {
                    prefix: 'select',
                    camelCase: false
                },
                onInit: function() {
                    const $select = $(this); /* сам скрываемый тег select */
                    $wrap = $select.parents('.select-wrapper').find('ul');
                    $item = $select.parents('.select-wrapper').find('.select');
                    $items = $wrap.find('li');
                    if ($isWhite) $item.addClass('select--white');
                    $select.addClass('hidden');
                    $wrap.addClass('select-ul');

                    let $listWrap = $select.parents('.select-wrapper').find('.select-scroll');
                    if ($listWrap.length > 0) {
                        $scroll = new PerfectScrollbar($listWrap[0], {
                            wheelSpeed: 0.5,
                            wheelPropagation: false,
                            minScrollbarLength: 20,
                            suppressScrollX: true
                        });
                    }
                },
                onOpen: function() {
                    const $select = $(this);
                    if ($select.parents('.b-form__item').find('.b-form__label').length) {
                        $select.parents('.b-form__item').find('.b-form__label').addClass('active');
                    }

                    $scroll.update();

                    if (!$this.parents('[data-select-wrap]'))
                        return;
                    let $filter = $this.parents('[data-select-wrap]').find('[data-select-sort]');
                    $filter.focus();
                    $filter.on('input', function () {
                        if ($filter.val()) {
                            $this.parents('.select-wrapper').addClass('select-sorted');
                        }
                        else {
                            $this.parents('.select-wrapper').removeClass('select-sorted');
                        }
                    })
                },
                onClose: function() {
                    const $select = $(this);
                    if ($select.parents('.b-form__item').find('.b-form__label').length && $select.val() === '') {
                        $select.parents('.b-form__item').find('.b-form__label').removeClass('active');
                    }
                    if (!$this.parents('[data-select-wrap]'))
                        return;
                    let $filter = $this.parents('[data-select-wrap]').find('[data-select-sort]');
                    $filter.blur();
                    if ($filter.val()) {
                        $this.parents('.select-wrapper').addClass('select-sorted');
                    }
                    else {
                        $this.parents('.select-wrapper').removeClass('select-sorted');
                    }
                },
                onChange: function() {
                    $(this).trigger('change');
                },
                arrowButtonMarkup: '',
                maxHeight: 'auto',
                disableOnMobile: false,
                nativeOnMobile: false
            });
            if ($this.parents('[data-select-wrap]').find('[data-select-sort]').length) {
                $filter = $this.parents('[data-select-wrap]').find('[data-select-sort]');
                $filter.on('click', () => {
                    $this.selectric('open');
                });
                $filter.on('keyup', () => {
                    $items.css('display','none');
                    $scroll.update();
                    $items.filter(idx => {
                        return $items[idx].innerText && $items[idx].innerText.toUpperCase().indexOf($filter.val().toUpperCase()) !== -1;
                    }).css('display','block');
                });
            }
        });
    },

    reg() {
        $('select[data-select-reg]').each(function () {
            const $this = $(this);
            let $isWhite = $this.is('.select--white');
            let $wrap;
            let $filter;
            let $item;
            let $items;
            let $scroll;

            $this.selectric({
                customClass: {
                    prefix: 'select',
                    camelCase: false
                },
                onInit: function() {
                    const $select = $(this); /* сам скрываемый тег select */
                    $wrap = $select.parents('.select-wrapper').find('ul');
                    $item = $select.parents('.select-wrapper').find('.select');
                    $items = $wrap.find('li');
                    if ($isWhite) $item.addClass('select--white');
                    $select.addClass('hidden');
                    $wrap.addClass('select-ul');

                    let $listWrap = $select.parents('.select-wrapper').find('.select-scroll');
                    if ($listWrap.length > 0) {
                        $scroll = new PerfectScrollbar($listWrap[0], {
                            wheelSpeed: 0.5,
                            wheelPropagation: false,
                            minScrollbarLength: 20,
                            suppressScrollX: true
                        });
                    }
                },
                onOpen: function() {
                    const $select = $(this);

                    if ($select.parents('.b-form__item').find('.b-form__label').length) {
                        $select.parents('.b-form__item').find('.b-form__label').addClass('active');
                    }

                    $scroll.update();

                    if (!$this.parents('[data-select-wrap]'))
                        return;
                    let $filter = $this.parents('[data-select-wrap]').find('[data-select-sort]');
                    $filter.focus();
                    $filter.on('input', function () {
                        if ($filter.val()) {
                            $this.parents('.select-wrapper').addClass('select-sorted');
                        }
                        else {
                            $this.parents('.select-wrapper').removeClass('select-sorted');
                        }
                    })
                },
                onClose: function() {
                    const $select = $(this);

                    if ($select.parents('.b-form__item').find('.b-form__label').length && $select.val() === '') {
                        $select.parents('.b-form__item').find('.b-form__label').removeClass('active');
                    }
                    if (!$this.parents('[data-select-wrap]'))
                        return;
                    let $filter = $this.parents('[data-select-wrap]').find('[data-select-sort]');
                    $filter.blur();
                    if ($filter.val()) {
                        $this.parents('.select-wrapper').addClass('select-sorted');
                    }
                    else {
                        $this.parents('.select-wrapper').removeClass('select-sorted');
                    }
                },
                onChange: function() {
                    $(this).trigger('change');
                },
                arrowButtonMarkup: '',
                maxHeight: 'auto',
                disableOnMobile: false,
                nativeOnMobile: false
            });
            if ($this.parents('[data-select-wrap]').find('[data-select-sort]').length) {
                $filter = $this.parents('[data-select-wrap]').find('[data-select-sort]');
                $filter.on('click', () => {
                    $this.selectric('open');
                });
                $filter.on('keyup', () => {
                    $items.css('display','none');
                    $scroll.update();
                    $items.filter(idx => {
                        return $items[idx].innerText && $items[idx].innerText.toUpperCase().indexOf($filter.val().toUpperCase()) !== -1;
                    }).css('display','block');
                });
            }
        })
    },

    personal() {
        const $select = $('[data-chart-select]');

        if(!$select.length)
            return;

        $select.on('change', () => alert(`/local/client/app/js/select.js personal() ${$select.val()}`))
    },

    catalogSort() {
        $('[data-select-catalog-sort]').on('change', function (e) {
            window.location = $(this).find('option:selected').data('url')
        });

        $('[data-select-catalog-count]').on('change', function (e) {
            window.location = $(this).find('option:selected').data('url')
        })
    },

    orderSort() {
        $('[data-select-orders-sort]').on('change', function (e) {
            order.formSubmitAjax($(this).data('url'));
        });
    },

    inputsOrders() {
        $("select#filter").on('change', function() {
            if ($(this).val() === 'date') {
                $('#input_id').addClass('hidden');
                $('#input_date').removeClass('hidden');
            } else if ($(this).val() === 'id') {
                $('#input_date').addClass('hidden');
                $('#input_id').removeClass('hidden');
            }
        });
    }
};

module.exports = select;
