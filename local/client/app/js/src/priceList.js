export class PriceListKP {
  constructor() {
    this.tableToPDF();
    this.tableToXLS();
    this.submitFormPriceListOrderKP();
    this.priceListReady();
    this.eyeClick();
  }

  eyeClick() {
    $(document).on('click', '[data-price-eye]', function () {
      let $this = $(this);
      let form = $this.closest('form');
      let item = $this.closest('[data-price-item]');
      form.find('input[type=checkbox]').prop('checked', false);
      item.find('input[type=checkbox]').prop('checked', true);
      form.submit();
    })
  }

  tableToPDF() {
    $(document).on('click', '#createPDF', function () {
      var table = [];
      let header = '<tr><td>Артикул</td><td>Количество</td><td>Наименование</td><td>Цена, шт.</td></tr>';
      table.push(header);
      $(".basket-item:not(.basket-item--top)").each(function () {
        let article = $(this).find('.basket-item__article').text();
        let count = $(this).find('.basket-item__count').find('input[type=text]').val();
        let name = $(this).find('.basket-item__description').text();
        let rawPrice = $(this).find('.basket-item__price').text().trim().replace(' ', '.');
        let row = '<tr><td>'+article+'</td><td>'+count+'</td><td><b>'+name+'</b></td><td><b>'+rawPrice+'</b></td></tr>';
        table.push(row);
      });
      var fd2 = new FormData;
      fd2.append('HTML', table);
      $.ajax({
        url: '/personal/price/order/pdf.php',
        data: fd2,
        processData: false,
        contentType: false,
        dataType: 'html',
        type: 'POST',
        beforeSend: function () {
          BX.showWait();
        },
        complete: function () {
          BX.closeWait();
        },
        success: function (data) {
          $('#rezult').html(data);
        },
        error: function (xhr, str) {
          alert('Возникла ошибка: ' + xhr.responseCode);
        }
      });
      return false;
    });
  }

  tableToXLS() {
    $(document).on('click', '#createXLSX', function () {
        var table = '';
        $(".basket-item:not(.basket-item--top)").each(function () {
            let article = $(this).find('.basket-item__article').text().trim() + '\n';
            let count = $(this).find('.basket-item__count').find('input[type=text]').val().trim() + '\n';
            let name = $(this).find('.basket-item__description').text().trim() + '\n';
            let rawPrice = $(this).find('.basket-item__price .basket-item__title').text().trim().replace(' ', '.')+'\n';
            let row = '<tr><td>' + article + '</td><td>' + count + '</td><td>' + name + '</td><td>' + rawPrice + '</td></tr>';

            table += row;
        });

        var fd2 = new FormData;
        fd2.append('HTML', table);
        $.ajax({
          url: '/personal/price/order/xls.php',
          data: fd2,
          processData: false,
          contentType: false,
          dataType: 'html',
          type: 'POST',
          beforeSend: function () {
            BX.showWait();
          },
          complete: function () {
            BX.closeWait();
          },
          success: function (data) {
            $('#rezult').html(data);
          },
          error: function (xhr, str) {
            alert('Возникла ошибка: ' + xhr.responseCode);
          }
        });
        return false;
      }
    );
  }

  submitFormPriceListOrderKP() {
    $(document).on('submit', '#formPriceListKP', function () {
      var idEl = [];
      var count = [];
      var mapIDtoCount = {};
      $("input[data-input-count-input]").each(function () {
        if ($(this).val() != "") {
          let id = $(this).prop('name');
          let elemCount = $(this).val();
          mapIDtoCount[id] = elemCount;
          idEl.push($(this).prop('name'));
          count.push($(this).val());
        }
      });
      var fd = new FormData;
      fd.append('PRODUCT_ID', idEl);
      fd.append('QUANTITY', count);
      fd.append('MAP_TO_COUNT', JSON.stringify(mapIDtoCount));
      $.ajax({
        url: '/personal/price/order/add.php',
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'html',
        type: 'POST',
        beforeSend: function () {
          BX.showWait();
        },
        complete: function () {
          BX.closeWait();
        },
        success: function (data) {
          $('#rezult').html(data);
        },
        error: function (xhr, str) {
          alert('Возникла ошибка: ' + xhr.responseCode);
        }
      });
      return false;
    })

  }

  priceListReady() {
    if ($(".basket-item").length === 0) {
      $("#submit_form.b-form__submit").remove();
      $("#download_price").remove();
      $("#error_price").show();
      $(".b-form__submit").show();
    }

    $('[data-item-delete]').on('click', function () {
      $(this).closest('[data-container-item]').remove();
    });
  }

}