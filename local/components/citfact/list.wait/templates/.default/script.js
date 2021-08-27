BX.ready(function(){

	// удаление уведомления
	$(document).find("a[href='/personal/list-wait/']").each(function(){
		if ($(this).html().includes("<span>")) {
			$(this).html($(this).html().split("<span>")[0]);
		}
	});


	// ввод числа с клавиатуры
	$(".count_ajax").change(function() {
		var element = $(this);
		BX.ajax({   
			url: '/local/ajax/add_to_list_wait.php',
			data: {
				'product': BX.data(this, 'itemId'),
				'count': $(this).val(),
				'action': 'edit'
			},
			method: 'POST',
			onsuccess: function(){
				// скрывать кнопку, если кол-во на складе меньше текущего кол-ва
				var buttonAdd = element.closest('.list-wait__btns').find('.js-basket-add');

				if (+element.val() <= +BX.data(buttonAdd[0], 'stock')) {
					buttonAdd.removeClass('disabled');
				} else {
					buttonAdd.addClass('disabled');
				}

				// надпись на кнопке
				if (element.val() == BX.data(buttonAdd, 'inbasket')) {
					buttonAdd.innerHTML = 'В корзине';
				} else {
					buttonAdd.innerHTML = 'В корзину';
				}
			}
		});
	});

	// крестик "удалить"
	$(".plus--cross").click(function() {
		var element = $(this);
		BX.ajax({   
			url: '/local/ajax/add_to_list_wait.php',
			data: {
				'product': BX.data(this, 'itemId'),
				'action': 'delete'
			},
			method: 'POST',
			onsuccess: function(){
				if (element.closest('.list-wait__item').length) {
					element.closest('.list-wait__item').remove();
					// если товаров больше нет, то нужно удалить заголовок таблицы 
					if (!($('.list-wait__item').length)){
						$('.list-wait__head').after($('<H3>', {
							text: 'Лист ожидания пуст'
						}));
						$('.list-wait').css('min-height', '50px');
						$('.list-wait').css('margin-bottom', '0');
						$('.list-wait__head').each(function(){
							$(this).remove();	 
						});
					}
				}
			}
		});
	});


	//функция чекбокса для отдельных товаров
	$(document).on('change', ".checkbox-ajax", function (e) {
		let ids = [];
		let id = $(this).attr('data-item-id');
		ids.push(id);
		let checked = e.target.checked;

		let arrCheckbox  = [];
		$('.checkbox-ajax').each(function () {
			arrCheckbox.push( $(this).prop('checked'));
		});
		arrCheckbox.splice(0,1);

		let index = $.inArray( false, arrCheckbox);

		 if(index == -1){
			 $('#ag-checkbox').attr('checked', true);
		 } else {
			 $('#ag-checkbox').attr('checked', false);
		 }

		BX.ajax({
			url: '/local/ajax/allow_notification_list_wait.php',
			data: {
				'id': ids,
				'checked': checked,
			},
			method: 'POST',
			onsuccess: function (res) {
                if(res  == 'ok') {
                    if (e.target.checked == true) {
                        $('.notificaction-green[data-notification =' + id + ']').removeClass('hidden');
                        $('.notificaction-red[data-notification =' + id + ']').addClass('hidden');

                    } else {
                        $('.notificaction-green[data-notification =' + id + ']').addClass('hidden');
                        $('.notificaction-red[data-notification =' + id + ']').removeClass('hidden');
                    }
                } else {
                    $(this).prop('checked', !checked);
                }
			}
		});
	});

	//функция для чекбокса для всех товаров
	$(document).on('change', "#ag-checkbox", function (e) {
		var id = [];
		var allCheckbox = $('[data-item-id]');
		var checked = e.target.checked;

		allCheckbox.each(function (key, checkbox) {
			id.push($(checkbox).attr('data-item-id'));
		});

		BX.ajax({
			url: '/local/ajax/allow_notification_list_wait.php',
			data: {
				'id': id,
				'checked': checked,
			},
			method: 'POST',
			onsuccess: function () {
				if (e.target.checked == true) {
					$('.notificaction-green').removeClass('hidden');
                    $('.notificaction-red').addClass('hidden');
                } else {
					$('.notificaction-green').addClass('hidden');
					$('.notificaction-red').removeClass('hidden');
				}
			}
		});
	});

	// кнопки "+" и "-"
	$('.b-count__btn').click(function() {
		var element = $(this);
		setTimeout(function() {
			if (element.closest('.b-count').children('input').length) {
				var newCount = element.closest('.b-count').children('input').attr('data-value');
				BX.ajax({   
					url: '/local/ajax/add_to_list_wait.php',
					data: {
						'product': element.closest('.b-count').children('input').attr('data-itemid'),
						'count': newCount,
						'action': 'edit'
					},
					method: 'POST',
					onsuccess: function(){
						// скрывать кнопку, если кол-во на складе меньше текущего кол-ва
						var buttonAdd = element.closest('.list-wait__btns').find('.js-basket-add');

						if (+newCount <= +BX.data(buttonAdd[0], 'stock')) {
							buttonAdd.removeClass('disabled');
						} else {
							buttonAdd.addClass('disabled');
						}

						// надпись на кнопке
						if (newCount == BX.data(buttonAdd, 'inbasket')) {
							buttonAdd.innerHTML = 'В корзине';
						} else {
							buttonAdd.innerHTML = 'В корзину';
						}
					}
				});
			}
		}, 0)
	});
});

