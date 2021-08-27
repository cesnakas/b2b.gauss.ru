$(document).on('click', '[data-show-more]', loadMoreItems);

$( document ).ready(function() {
    var btn = $('a[data-show-more]');
    if (btn.data('last-page')<=0)
    {
        btn.remove();
    }
});

function loadMoreItems() {
    var btn = $('a[data-show-more]');
    var nextPage = parseInt(btn.attr('data-current-page')) + 1;

    var data = {
        PAGEN: nextPage
    };

    $.ajax({
        type: "GET",
        url: window.location.href,
        data: data,
        success: function (data) {
            let loadedItems = $(data).find('.lk-receivables__item');
            $(".lk-receivables__item:last").after(loadedItems);

            let loadedButton = $(data).find('[data-show-more]');
            if (loadedButton.length == 0) {
                btn.remove();
                return;
            }
            Am.modals.run();
            let currentPage = loadedButton.attr('data-current-page');
            btn.attr('data-current-page', currentPage);
            let lastPage = loadedButton.attr('data-last-page');
            if (currentPage >= lastPage)
                btn.remove();
        }
    });
}

function selectSortChange($this) {
    var sort = $this.val();
    var name = $this.attr('name');
    var url = new URL(window.location);
    url.searchParams.set(name, sort);
    history.pushState(null, null, url);
    window.location.reload();
}
