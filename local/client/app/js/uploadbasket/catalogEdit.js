import Vue from 'vue';
import {BasketRequest} from  './basketRequest'
import {VueContainer} from  './vueContainer'

export class CatalogEdit extends Vue {
    constructor(data) {
        super({
            data : data,
            el: '#catalog-edit',
            components: CatalogEdit.components,
            methods: CatalogEdit.methods
        });

        VueContainer.add('catalog-edit', this);

    }

    static methods = {
        'send' : function(method, data){
            let r = new BasketRequest();
            let self = this;

            return r.send(method, data)
                .then(data => self.$data.data.mode = data.data.mode);
        },
        /*'changeItem' : function(itemId){
          this.send('changeItem', {'itemId' : itemId}).then(function (x) {
            window.location.href = '/personal/load_order/';
          });
        },*/
        'getMode' : function(){
            this.send('getData', null);
        }
    }

}