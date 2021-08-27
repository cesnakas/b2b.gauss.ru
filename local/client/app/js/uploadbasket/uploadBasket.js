import Vue from 'vue';
import {VueContainer} from  './vueContainer';
import {BasketRequest} from  './basketRequest';

import BasketForm from './components/basket-form';
import InputForm from './components/input-form';

class Loader  {
    start() {
        this.loader = BX.showWait()
    }

    stop() {
        BX.closeWait(this.loader);
    }
}

export class UploadBasket extends Vue {
    constructor(data) {
        super({
            data : data,
            el: '#upload-basket',
            components: UploadBasket.components,
            methods: UploadBasket.methods,
        });

        this.loader = new Loader();

        VueContainer.add('upload-basket', this);
    }
    static components = {
        'basket-form': BasketForm,
        'input-form': InputForm,
    };

    static methods = {
        'send' : function(method, data){
            let r = new BasketRequest();
            let self = this;
            let result;
            if (method === 'importFile')
                result = r.sendFile(data);
            else
                result = r.send(method, data);

            result.then(function (data) {

                if (data.data &&  data.data.hasOwnProperty("basket")){
                    self.data.basket = data.data.basket;
                } else {
                    self.data.basket = [];
                }


                self.data.message = data.message;
                self.message = data.message;

                self.data.status = data.status;
                self.status = data.status;

                self.data.type = data.type;
                self.type = data.type;

                // console.log(self);


            });

            return result;
        },
        'clearBasket' : function(){
            return this.send('clearBasket', null);
        },
        'deleteItem' : function (itemHash) {
            this.send('deleteItem', {'itemId' : itemHash});
        },
        'increaseQuantityItem' : function (itemHash) {
            this.send('increaseQuantityItem', {'itemId' : itemHash});
        },
        'decreaseQuantityItem' : function (itemHash) {
            this.send('decreaseQuantityItem', {'itemId' : itemHash});
        },
        'editQuantityItem' : function (itemHash, userQuantity) {
            this.send('editQuantityItem', {'itemId' : itemHash, 'quantity': userQuantity});
        },
        /*'selectItem' : function (itemHash) {
          this.send('selectItem', {'itemId' : itemHash});
          window.location.href  = '/catalog/';
        },*/
        'addItemsAndMoveImport' : function () {
            this.loader.start();
            this.send('addItemsAndMoveImport', null)
                .then(() => {
                    location.reload();
                    this.loader.stop();
                });
        },
        'addItemsAndMoveBasket' : function () {
            this.loader.start();
            this.send('addItemsAndMoveBasket', null)
                .then(() => {
                    window.location.href = '/cart/';
                })
        }
    }

}