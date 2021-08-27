document.addEventListener('App.Ready', function (e) {

    var arrId = [];

    function HandlerBtnAddWait() {
        var items = $('.c-t__item');

        setTimeout(function () {
            items.each(function (index, item) {

                var btn = item.querySelector('.btn-wait');
                var input = item.querySelector('.b-count__input');

                if (btn) {
                    var itemid = btn.dataset.itemid;

                    if (!arrId.includes(itemid)) {
                        arrId.push(itemid);

                        btn.addEventListener('click', function () {

                            if (input) {
                                var count = input.value;
                            } else {
                                var count = 1;
                            }
                            count = (+count > 1 ? +count : 1);

                            BX.ajax({
                                url: '/local/ajax/add_to_list_wait.php',
                                data: {
                                    'product': itemid,
                                    'count': count
                                },
                                method: 'POST',
                                onsuccess: function (data) {
                                    btn.classList.add('active');
                                }
                            });
                        });
                    }
                }
            })
        }, 500)
    }

    HandlerBtnAddWait();

    $( document ).on( 'click', '[data-show-more]', loadMoreItems );

    var isLoading = false;

    /*$( window ).scroll( function () {
        if ( !isLoading && $( window ).scrollTop() >= ( $( document ).height() - $( window ).height() ) * 0.9 && !!Am.tooltip ) { //когда прокрутка достигает 60% высоты окна
            loadMoreItems();
        }
    } );*/

    $( document ).on( 'change', '[data-item-id], [data-catalog-select-all]', setCheckedItemsCount );

    function loadMoreItems() {
        isLoading = true;
        var btn = $( 'a[data-show-more]' );
        var loader = $( '.btn--loader' );
        var page = btn.attr( 'data-next-page' );
        var id = btn.attr( 'data-show-more' );
        var bx_ajax_id = btn.attr( 'data-ajax-id' );
        var block_id = "#comp_" + bx_ajax_id;

        if ( !bx_ajax_id ) {
            return;
        }

        loader.removeClass( 'hidden' );

        var data = {
            bxajaxid: bx_ajax_id
        };
        data[ 'PAGEN_' + id ] = page;

        btn.addClass('active');

        $.ajax( {
            type: "GET",
            url: window.location.href,
            data: data,
            success: function ( data ) {
                $( "#btn_" + bx_ajax_id ).remove();
                $( block_id + " .c-t__item:last" ).after( data );
                setItemsCount();
                setCheckedItemsCount();
                isLoading = false;
                Am.tooltip.run();
                Am.modals.run();
                Am.counter.run();
                /*Am.CatalogActions.run();*/
                BX.onCustomEvent( 'OnBasketChange' );
                HandlerBtnAddWait();
            },
            complete: function () {
                loader.remove();
            }
        } );
    }

//устанавливает количество выбранных товаров
    function setCheckedItemsCount() {
        var checkedCount = $( '[data-item-id]:checked' ).length;
        if ( checkedCount > 0 ) {
            $( '.c__text' ).show();
            document.getElementById('click__btn').disabled=false;
        } else {
            $( '.c__text' ).hide();
            document.getElementById('click__btn').disabled=true;
        }
        $( '#selected-items-count' ).html( checkedCount );
    }

//устанавливает количество товаров на странице
    function setItemsCount() {
        var itemsCount = $( '[data-item-id]' ).length;
        $( '#items-count' ).html( itemsCount );
    }

});