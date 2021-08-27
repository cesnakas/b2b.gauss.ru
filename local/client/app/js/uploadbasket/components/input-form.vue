<template>
    <form v-if="isEmptyBasket()" @submit="sendData">
        <div class="order-upload">
            <div class="b-tabs" data-tab-group>
                <div class="b-tabs-head" data-tab-header>
                    <a href="javascript:void(0);"
                       class="b-tabs-link"
                       title="Вставить скопированный текст"
                       :class="{ active :activeTab == 'text'}"
                       @click="selectTab('text')"
                    >
                        Вставить скопированный текст
                    </a>
                    <a href="javascript:void(0);"
                       class="b-tabs-link"
                       title="Загрузить заказ из файла"
                       :class="{ active :activeTab == 'file'}"
                       @click="selectTab('file')"
                    >
                        Загрузить заказ из файла
                    </a>
                </div>
                <div class="b-tabs__content" data-tab-content>
                    <div class="b-tabs__item"
                         :class="{ active :activeTab == 'text'}"
                    >
                        <div class="order-upload__wrap">
                            <div class="order-upload__left">
                                <form class="b-form b-form--small"
                                      action="">

                                    <div class="b-form__item b-form__item--textarea"
                                         :class="type === 'text' ? 'error' : ''"
                                         data-f-item>
                            <span class="b-form__label" data-f-label>Скопируйте и вставьте текст из таблицы<span>, например:
                                    <br> 62120 2
                                    <br> 93093 2
                                </span>
                            </span>

                                        <textarea name="input"
                                                  class="b-form__textarea"
                                                  v-model="rawDataText"
                                                  data-f-field
                                                  data-component="uploadable-basket-input"></textarea>

                                        <span class="b-form__text">
                                {{ message }}
                            </span>
                                    </div>
                                </form>

                                <div class="b-form__bottom">

                                    <button @click="resetData()"
                                            class="btn btn--grey btn--big"
                                            data-action="cancel">
                                        Отменить
                                    </button>
                                    <button class="btn btn--transparent btn--big " :class="{'no-hover': !rawDataText}"
                                            data-action="preview">
                                        Далее
                                    </button>
                                </div>
                            </div>
                            <div class="order-upload__right">
                                <div class="order-upload__list styled-list">
                                    <ul>
                                        <li class="b-order-steps__item"><span class="text--highlight">Шаг 1:</span>
                                            Выделить
                                            таблицу так, чтобы первым столбцом были артикулы, а вторым –
                                            количество
                                            товара.
                                        </li>
                                        <li class="b-order-steps__item"><span class="text--highlight">Шаг 2:</span>
                                            Щелкнуть
                                            правой кнопкой мыши по выделенной области и выбрать из открывшегося меню
                                            пункт
                                            «Копировать».
                                        </li>
                                        <li class="b-order-steps__item"><span class="text--highlight">Шаг 3:</span>
                                            Щелкнуть
                                            правой кнопкой мыши внутри поля «Вставьте скопированный текст» на портале и
                                            выбрать пункт «Вставить» из появившегося меню.
                                            <br>
                                            Нажать кнопку «Далее».
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="b-tabs__item"
                         :class="activeTab == 'file' ? 'active' : ''"
                    >
                        <div class="order-upload__wrap">
                            <div class="order-upload__left">
                                <div class="styled-list">

                                    <div class="b-form__file" :class="type === 'file' ? 'error' : ''">
                                        <div class="b-form__upload">
                                            <label class="btn btn--grey full-width">
                                                <input name="fileImport"
                                                       @change="processFile"
                                                       id="file-upload"
                                                       accept=".csv, .xlsx, .xls, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                                       type="file">

                                                <span v-if="rawDataFile">
                                                    {{ rawDataFile.get('file-name') }}
                                                </span>

                                                <span v-else>
                                                    <svg class='i-icon'>
                                                        <use xlink:href='#icon-attachment'/>
                                                    </svg>
                                                    <span>Прикрепить файл</span>
                                                </span>
                                            </label>
                                        </div>


                                        <div class="b-form__button">
                                            <span class="b-form__format">Формат документа: XLSX, размер не более 10Мб</span>
                                            <span class="b-form__text" v-if="type === 'file'">{{ message }}</span>
                                            <button class="btn btn--transparent btn--big" :class="{'no-hover': !rawDataFile}">
                                                Далее
                                            </button>
                                        </div>
                                    </div>


                                    <p>
                                        <b>Примечание.<br/>
                                          При загрузке файла в своем формате необходимо выполнить следующее условие:</b>
                                        <br/>
                                        Наличие в файле  двух колонок (1. Артикул 2. Количество). Обязательно заполняйте только 1(A) и 2(B) колонки excel файла, как показано в примере ниже. Заголовки к колонкам задавать не нужно.
                                        <img src="/local/client/img/personal/ex_order.png" alt=""  class="img-fast-order">
                                    </p>
                                </div>
                            </div>
                            <div class="order-upload__right">
                                <div class="order-upload__list styled-list">
                                    <ul>
                                        <li class="b-order-steps__item order__steps--load">
                                        <span class="text--highlight">
                                            Шаг 1:
                                        </span>
                                        Скачайте образец файла <a v-bind:href="urlTemplateXls" title="Скачать пример файла">бланк-заказа </a>на свой ПК
                                        </li>
                                        <li class="b-order-steps__item">
                                    <span class="text--highlight">
                                        Шаг 2:
                                    </span>
                                            Введите в колонке «Заказ, кол-во» необходимое количество позиций и сохраните файл
                                        </li>
                                        <li class="b-order-steps__item order__steps--file">
                                    <span class="text--highlight">
                                        Шаг 3:
                                    </span>
                                            Нажмите кнопку «Выбрать файл» и выберите сохраненный файл. Через несколько секунд
                                            система сформирует заказ! <br/>Нажать кнопку "Далее".
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
<style>
    .order-top {
        display: flex;
        max-width: 463px;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;

        @media (max-width: 500px) {
            flex-direction: column-reverse;
            align-items: flex-start;
        }

        .link-download {
            @media (max-width: 500px) {
                margin-bottom: 10px;
            }
        }
    }
    .order-upload {
      padding: 40px;
      background: #fff;

      .b-form__button {
          justify-content: flex-end;
          display: flex;
          align-items: center;
          margin-bottom: 20px;
          .b-form__format {
              margin-right: auto;
          }
          button {
              margin-right: 20px;
          }
      }


      @media (min-width: 1024px) {
        padding-right: 100px;
      }

      @media (max-width: 500px) {
        padding: 0;
        background-color: transparent;
      }

      .b-tabs {
        margin-top: 0;
      }

      .b-tabs-head {
        margin-bottom: 28px;
      }

      .b-tabs-link {
        padding-bottom: 0;

        &:not(.active) {
          border-bottom: 1px dashed rgba(148, 155, 168, 0.4);
        }
      }

      .b-form {
        max-width: 100%;

        textarea {
          @media (min-width: 1024px) {
            min-height: 330px;
          }
        }
      }

      .b-form__bottom {
        justify-content: flex-end;

        > * {
          @media (max-width: 500px) {
            margin-left: 0 !important;
            margin-right: 0 !important;
            margin-bottom: 10px !important;
          }

          &:last-child {
            margin-right: 0;
            margin-left: 15px;

            @media (max-width: 500px) {
              margin-right: 0 !important;
              margin-bottom: 0 !important;
            }
          }
        }

        .b-form__format {
          margin-right: auto;
          font-size: 16px;
        }
      }
    }

    .order-upload__wrap {
      display: flex;

      @media (max-width: 1023px) {
        display: block;
      }
    }

    .order-upload__left {
      flex: 1 1 710px;
      margin-right: 100px;
      min-width: 0;

      @media (max-width: 1023px) {
        margin-right: 0;
        margin-bottom: 30px;
      }
    }

    .order-upload__right {
      flex: 1 1 500px;
      margin-top: -50px;

      @media (max-width: 1023px) {
        margin-top: 0;
      }
    }

    .order-upload__list {
      &.styled-list {
        ul {
          margin: 0;

          li {
            padding-left: 35px;
            margin-bottom: 26px;
            position: relative;

            &:last-child {
              margin-bottom: 0;
            }

            &::before {
              content: '';
              position: absolute;
              left: 0;
              top: 4px;
              width: 16px;
              height: 16px;
              border-radius: 100%;
              background: #F77B1D;
            }

            .text--highlight {
              display: block;
              margin-bottom: 6px;
              font-weight: 500;
              font-size: 18px;
              line-height: 1.55;
            }

            a {
              color: #F77B1D;
            }
          }
        }
      }
    }

    .order-upload__file {
      .btn {
        height: 133px;
        width: 100%;
        margin-right: 0;
        background-color: #ECEEF1;
        font-size: 22px;
        font-weight: 300;
        color: #949BA8;
        white-space: normal;
        text-align: center;

        @media (max-width: 500px) {
          height: 150px;
        }

        &:hover {
          background-color: #E1E4E8;
        }

        svg {
          margin-right: 5px;
        }

        .b-form__upload-label {
          border-bottom: 1px dashed rgba(148, 155, 168, 0.4);
        }
      }
    }

    .order-upload__terms {
      margin-top: 60px;
      font-size: 16px;

      p {
        margin-bottom: 14px;
      }

      ul, ol {
        list-style-position: inside;
      }

      ol {
        counter-reset: list;

        list-style-type: none;

        li::before {
          counter-increment: list;
          content: counter(list) ") ";
        }
      }
    }
</style>
<script>
  import {BasketRequest} from  '../basketRequest.js'
  import inputs from  '../../inputs'

  export default{
    props: ['data', 'status', 'message','type'],
    data : function() {
      return {
        'urlTemplateXls' : 'text',
        'activeTab' : 'text',
        'rawDataFile' : null,
        'rawDataText' : null
      }
    },
    created() {
      this.$root.$on('eventRestBasket', () => {
        this.rawDataFile = null;
        this.rawDataText = null;
        this.$parent.loader.stop();
      });
    },
    updated() {
      inputs.placeholder();
    },
    mounted() {
        let urlTemplateXls = null;
        if (document.getElementById('upload-basket')) {
            urlTemplateXls = document.getElementById('upload-basket').getAttribute('data-url');
        }
        this.urlTemplateXls = urlTemplateXls;

      inputs.placeholder();
    },
    methods: {
      isEmptyBasket : function() {
        return this.status && _.isEmpty(this.data.basket)
      },
      selectTab : function(type) {
        this.activeTab = type
      },
      loadFromText : function() {
        this.$parent.send('importText', this.rawDataText)
          .then((data) => {
            this.$parent.loader.stop();
          });
      },
      loadFromFile : function() {
        this.$parent.send('importFile',  this.rawDataFile)
          .then(() => {
            this.$parent.loader.stop();
          });
      },
      sendData : function(e) {
        e.preventDefault();
        switch(this.activeTab) {
          case 'text':
            if (this.rawDataText != null) {
              this.$parent.loader.start();
              this.loadFromText()
            }
            break;
          case 'file':
            if (this.rawDataFile != null) {
              this.$parent.loader.start();
              this.loadFromFile()
            }
            break;
        }
      },
      resetData : function() {
        this.rawDataFile = null
        this.rawDataText = null
      },

      processFile : function(e){
        const fieldName = e.target.name;
        const fileList = e.target.files;

        let formData = new FormData();

        if (!fileList.length) {
          this.rawDataFile = null;

          return;
        }

        Array
          .from(Array(fileList.length).keys())
          .map(x => {
            formData.append(fieldName, fileList[x], fileList[x].name);
            formData.append('file-name', fileList[x].name)
          });

        formData.append('method', 'importFile')

        window.t = formData;
        this.rawDataFile = formData
      }

    }


  }




</script>
