import Vue from 'vue';
import {VueContainer} from  './vueContainer'
import {BasketRequest} from  './basketRequest'

export class OrderEdit extends Vue {
    constructor(data) {
        super({
            data : data,
            el: '#order-edit',
            components: OrderEdit.components,
            methods: OrderEdit.methods
        });
        VueContainer.add('order-edit', this);
    }

    static methods = {
        'send' : function(method, data){
            let r = new BasketRequest();
            let self = this;
            return r.send(method, data)
                .then(() => {
                    self.$data.data.mode = data.data.mode;
                });
        },
        'getMode' : function(){
            this.send('getData', null);
        },
        'clearBasket' : function(){
            this.send('clearBasket', null)
                .then(() => {
                    BX.showWait();
                    location.reload();
                })
        }
    }
}