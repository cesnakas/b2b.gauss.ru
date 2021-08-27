<template>
    <form action=""
          v-if="status && !isEmptyBasket()">

        <div class="basket-item basket-item--top">
            <div class="basket-item__id">№ п/п</div>
            <div class="basket-item__article">Артикул</div>
            <div class="basket-item__description">Наименование</div>
            <div class="basket-item__state">Найдено</div>
            <div class="basket-item__price">Цена</div>
            <div class="basket-item__count">Количество</div>
            <div class="basket-item__price">Сумма</div>
            <div class="basket-item__actions"></div>
        </div>

        <div v-for="item, k in data.basket"
             :class="!item.found ? 'basket-item--nf' : ''"
             class="basket-item basket-item--fo">
            <div class="basket-item__id">
                <div class="basket-item__t">№ п/п</div>
                {{ ++k }}
            </div>
            <div class="basket-item__article">
                <div class="basket-item__t">Артикул</div>
                <span :title="item.article">{{ item.article }}</span>
            </div>
            <div class="basket-item__description">
                <span class="basket-item__title"><span>{{ k }}. &nbsp; </span>{{ item.name }}</span>
                <div class="basket-item__article">Артикул:&nbsp{{ item.article }}</div>
            </div>
            <div class="basket-item__state">
                <div class="basket-item__t">Найдено</div>
                <div v-if="item.found"
                     class="green">
                    <span>Да</span>
                    <span>Товар найден</span>
                </div>
                <div v-else
                     class="red">
                    <span>Нет</span>
                    <span>Товар не найден</span>
                </div>
            </div>
            <div class="basket-item__price">
                <div class="basket-item__t">Цена</div>
                <span>{{ item.price_format }}</span>
            </div>
            <div class="basket-item__count">
                <div class="basket-item__t">Количество</div>

                <div class="b-count" :class="!item.found ? 'disabled' : ''">
                    <button type="button"
                            @click="decreaseQuantityItem(item.hash, item.quantity)"
                            class="b-count__btn b-count__btn--minus"></button>
                    <input class="b-count__input" :value="item.quantity" @input="editQuantityItem(item.hash, $event.target.value)">
                    <button type="button"
                            @click="increaseQuantityItem(item.hash, item.quantity)"
                            class="b-count__btn b-count__btn--plus"></button>
                </div>
            </div>
            <div class="basket-item__price">
                <div class="basket-item__t">Сумма</div>
                <span>{{ item.sum_format }}</span>
            </div>
            <div class="basket-item__actions">
                <div class="plus plus--cross"
                     @click="deleteItem(item.hash)"></div>
            </div>
        </div>

        <div class="b-form__bottom">
            <a href="javascript:void(0);"
               class="btn btn--grey btn--big"
               title="Отменить"
               @click="clearBasket">
                Отменить
            </a>
            <a href="javascript:void(0);"
               @click="addItemsAndMoveImport"
               class="btn btn--transparent btn--big"
               title="Добавить и вернуться">
                Добавить и вернуться
            </a>
            <a href="javascript:void(0);"
               @click="addItemsAndMoveBasket"
               class="btn btn--transparent btn--big"
               title="Добавить и уточнить заказ">
                Добавить и уточнить заказ
            </a>
        </div>
    </form>
</template>
<style>

</style>
<script>

    export default{
        props: ['data', 'status'],
        methods: {
            isEmptyBasket : function() {
                return _.isEmpty(this.data.basket)
            },
            clearBasket : function() {
                this.$parent.loader.start();
                this.$parent.clearBasket()
                    .then(() => {
                        this.$root.$emit('eventRestBasket');
                    });
            },
            deleteItem : function(hash) {
                this.$parent.deleteItem(hash);
            },
            increaseQuantityItem : function(hash) {
                this.$parent.increaseQuantityItem(hash);
            },
            decreaseQuantityItem : function(hash, quantity) {
                if (1 < quantity) {
                    this.$parent.decreaseQuantityItem(hash);
                }
            },
            editQuantityItem : function(hash, userQuantity) {
                if (!!this.timeout) {
                    clearTimeout(this.timeout);
                }

                this.timeout = setTimeout(() => {
                    let quantity = isNaN(parseInt(userQuantity)) ? 1 : parseInt(userQuantity);
                    this.$parent.editQuantityItem(hash, quantity);
                }, 500);

            },
            /*selectItem : function(hash) {
              this.$parent.selectItem(hash);
            },*/
            addItemsAndMoveImport : function(){
                this.$parent.addItemsAndMoveImport();
            },
            addItemsAndMoveBasket : function(){
                this.$parent.addItemsAndMoveBasket();
            }
        }
    }



</script>
