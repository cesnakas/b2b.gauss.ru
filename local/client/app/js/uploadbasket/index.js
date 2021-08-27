import {UploadBasket} from './uploadBasket'
import {CatalogEdit} from './catalogEdit'
import {BasketRequest} from './basketRequest'
import {OrderEdit} from './orderEdit'
import 'jquery-pjax';


export default class UploadableBasket {

    constructor() {
        if ($("#upload-basket").length) {

            this.initUploadBasket();

        } else if ($("#order-edit").length) {

            this.initBasketControl();

            if ($("#catalog-edit").length){

                this.initCatalogControl();

            }
        }
    }

    initUploadBasket() {
        var wait = BX.showWait();
        this.exec('getData')
            .then((data) => {
                let up = new UploadBasket(data);
                BX.closeWait('', wait);
            });
    }

    initBasketControl() {
        this.exec('getData')
            .then((data) =>  {
                new OrderEdit(data);
            });
    }

    initCatalogControl() {
        this.exec('getData')
            .then((data) =>  {
                new CatalogEdit(data);
            });
    }

    exec(method, data) {
        let r = new BasketRequest();
        return r.send(method, data);
    }
}