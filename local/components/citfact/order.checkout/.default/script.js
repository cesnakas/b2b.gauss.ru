OrderMake = {
  params:{},
  BXCallAllowed:false,

  /**
   * инициализация работы с формой заказа
   * @param params
   */
  init: function (params) {
    var _this = this;
    this.params = params;

    var form = $(_this.params.wrapId).find('form');

    /**
     * навешивание обработчиков на элементы формы (измение select, etc ...)
     */
    _this.eventsChanges(form);

    /**
     * действия при отправке формы
     */
    _this.submitForm(form);


    /**
     * при изменении местопложения, апдейтим форму
     * submitFormProxy - указан в классе компонента
     * @type {jQuery|*|*|never}
     */
    window.submitFormProxy = BX.proxy(function(){
      _this.submitFormProxy.apply(_this, arguments);
    }, this);

    /**
     * при инициализации скрипта
     * включаем адпейт формы при изменении местоположения
     */
    BX(function(){
      _this.BXCallAllowed = true; // unlock form refresher
    });
  },

  /**
   * навешивание обработчиков на элементы формы (измение select, etc ...)
   * @param form
   */
  eventsChanges: function(form) {
    var _this = this;

    var inputChanges = $(_this.params.wrapId).find('[data-change-order]');
    inputChanges.on('change', function () {
      _this.updateForm();
    });

    var button = $(_this.params.wrapId).find('[data-order-checkout-submit]');
    button.on('click', function (e) {
      e.preventDefault();
      form.submit();
    });
  },


  /**
   * действия при отправке формы
   * @param form
   */
  submitForm: function(form) {
    var _this = this;

    form.submit(function (e) {
      e.preventDefault();

      /**
       * валидация ошибок на js
       * @type {*|Array}
       */
      // let errors = Am.Validation.validateForm(form);
      // if (errors.length) {
        // Am.Validation.showErrors(form, errors);
        // Am.Validation.scrollTopError(errors);
      //   return;
      // }

      /**
       * у всех селектов по умолчанию выделены первые пункты
       * но артибут selected пустой
       * таким образом определяем, что селект на самом деле не был чекнут
       * и удаляем из post
       */
      var formData = new FormData(form[0]);
      form.find('[data-selected]').each(function () {
        var select = $(this);
        var selectName = select.attr('name');
        var selectedOption = select.find('option:selected');
        if (selectedOption.attr('selected') !== 'selected') {
          formData.delete(selectName);
        }
      });

      /**
       * отправка данных формы - сохранение
       */
      _this.sendData(formData);
    });
  },


  /**
   * при изменении местопложения, апдейтим форму
   * при отправка формы - блокируем апдейт формы при изменении местоположения
   * до получения результата формы
   * @type {jQuery|*|*|never}
   * @param $val
   * @param $component
   */
  submitFormProxy: function ($val, $component) {
    if($val && $val !== 'other'){
      if(this.BXCallAllowed){
        this.BXCallAllowed = false;
        setTimeout(function(){
          OrderMake.updateForm()
        }, 20);
      }
    }
  },

  /**
   * обновление формы без сохранения
   */
  updateForm: function() {
    var _this = this;

    var form = $(_this.params.wrapId).find('form');
    var formData = new FormData(form[0]);
    formData.delete('save');
    formData.append('update', 'Y');

    /**
     * у всех селектов по умолчанию выделены первые пункты
     * но артибут selected пустой
     * таким образом определяем, что селект на самом деле не был чекнут
     * и удаляем из post
     * сделано для доставок, чтобы при первом аякс запросе не выделять все доставки и не раситывать их стоимости
     */
    form.find('[data-selected]').each(function () {
      var select = $(this);
      var selectName = select.attr('name');
      var selectedOption = select.find('option:selected');
      if (selectedOption.attr('selected') !== 'selected') {
        formData.delete(selectName);
      }
    });

    _this.sendData(formData);
  },


  /**
   * отправка фрмы - сохранение
   * @param formData
   */
  sendData: function(formData) {
    var _this = this;

    BX.showWait();
    _this.BXCallAllowed = false;

    // $('[data-order-error-js]').hide();

    /**
     * аякс запрос в компонент (обновление/сохранение)
     * @type {string}
     */
    var object = {};
    formData.forEach(function(value, key){
      object[key] = value;
    });
    var json = JSON.stringify(object);
    var request = BX.ajax.runComponentAction('citfact:order.make', 'createOrder', {
      mode:'class',
      data: {
        jsonFormData: json
      },
      signedParameters: _this.params.signedParameters
    });


    request.then(function(response){
      if (response.data.REDIRECT_PAGE) {
        window.location = response.data.REDIRECT_PAGE;

      } else {
        BX.closeWait();

        let container = $(_this.params.wrapId);

        /**
         * записываем страницу html
         */
        container.replaceWith(response.data.RESPONSE.html);

        /**
         * т.к. новый html то и контейнер новый
         * @type {*|jQuery.fn.init|jQuery|HTMLElement}
         */
        container = $(_this.params.wrapId);
        let form = container.find('form');


        /**
         *  при изменении местопложения, апдейтим форму
         */
        _this.submitForm(form);

        /**
         * навешивание обработчиков на элементы формы (измение select, etc ...)
         */
        _this.eventsChanges(form);


        /**
         * после аякса навешиваем стайлеры
         */
        // window.Am.inputMask.run();
        // window.Am.toggleList.run(container);
        // window.Ac.select.run(container);
        // window.Am.tooltip.run(container);
        // window.Am.sticky.run();

        /**
         * для местоположения
         * после обновления формы включаем апдейт формы при смене местоположения
         */
        setTimeout(function () {
            _this.BXCallAllowed = true; // unlock form refresher
        }, 1000);
      }

    }).catch(function(e) {
      /**
       * если с сервера прилетел exception
       */
      BX.closeWait();
      setTimeout(function () {
        _this.BXCallAllowed = true; // unlock form refresher
      }, 1000);

      // let errors = [];
      // let wrapOrderErrors = $('[data-order-error-js]');
      // wrapOrderErrors.show();
      // errors.push({
      //   'entity': wrapOrderErrors.find('[data-error-js]'),
      //   'errorType':'incorrect',
      // });
      // Am.Validation.scrollTopError(errors);
    });
  },
};