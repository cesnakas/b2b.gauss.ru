
let catalogTable = {
    run() {

        $(window).resize(function() {
            /* catalog */
            $('.c-t .c-t__top .c-t__name').width($('.c-t .c-t__item .c-t__name').width());
            /* list-wait */
            $('.list-wait__head .list-wait__name').width($('.list-wait__inner .list-wait__name-w').width());
            /* lk */
            var agentCell = $('.account-table__row > [data-toggle-btn] + .account-table__agent');
            $('.account-table .account-search').width(agentCell.width());
        }).resize();
    },
};

module.exports = catalogTable;
