<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}


use Citfact\Sitecore\Order\OrderRepository;
use Citfact\Sitecore\UserDataManager;


/** @var array $arResult */
/** @var array $arParams */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var OrderCheckout $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var OrderCheckout $component */

$this->setFrameMode(false);
CJSCore::Init(array('currency'));
$page = $APPLICATION->GetCurPage();

$arContragent = UserDataManager\UserDataManager::getContrAgentInfo();
$phoneContragent = array_shift($arContragent['UF_TELEFON']);

global $USER;
$sName = $USER->GetSecondName(); // фамилия
$fName = $USER->GetFirstName(); // имя
$lName = $USER->GetLastName(); // отчество
if ($lName) {
    $strName = $lName . ' ';
    if ($fName) {
        $strName .= substr($fName, 0, 1).'.';
    }
    if ($sName) {
        $strName .= substr($sName, 0, 1).'.';
    }
} else {
    $strName = $fName . ' ' . $sName;
}

$productIterator = \Bitrix\Catalog\ProductTable::getList(array(
    'select' => array('ID', 'QUANTITY', 'QUANTITY_TRACE', 'CAN_BUY_ZERO'),
    'filter' => array('@ID' => array_column($arResult['BASKET_ITEMS'],'ID'))
));
$productItems = [];
while ($product = $productIterator->fetch())
{
    $productItems[$product['ID']] = $product;
}
$availableSum = 0;
foreach ($arResult['BASKET_ITEMS'] as $k => $item) {
    if($productItems[$item['ID']]['QUANTITY'] < 0){
        $productItems[$item['ID']]['QUANTITY'] = 0;
    }
    if($item['BASKET']['QUANTITY'] <= $productItems[$item['ID']]['QUANTITY']){
        $availableSum += $item['BASKET']['FINAL_PRICE'];
    } else {
        $availableSum += $item['BASKET']['PRICE'] * $productItems[$item['ID']]['QUANTITY'];
    }
}
$notAvailableSum = ($arResult['ORDER_PRICE'] > $availableSum) ? $arResult['ORDER_PRICE'] - $availableSum : 0;
/**
 * блокируем клики по форме, пока не загрузится страница
 */
?>
    <div class="order" id="order-checkout-wrap" onclick="return false;">
        <div class="order__sidebar order__sidebar--m">
            <div class="order-total">

                <div class="title-2">Ваш заказ</div>

                <div class="order-total__item order-total__item--r">
                    <span>Товаров на сумму:</span>
                    <span><?= $arResult['BASKET_PRICE_FORMAT']; ?></span>
                </div>

                <div class="order-total__item order-total__item--r">
                    <span>Доставка:</span>
                    <span><?= $arResult['DELIVERY_PRICE_FORMAT']; ?></span>
                </div>

                <div class="order-total__item">
                    <span>Общий вес:</span>
                    <span><?= round($arResult['VES_BRUTTO'], 1); ?> кг.</span>
                </div>

                <div class="order-total__item">
                    <span>Объем:</span>
                    <span><?= round($arResult['OBEM'], 2); ?> м <sup>3</sup></span>
                </div>

                <div class="order-total__item order-total__item--sum">
                    <span>Итого:</span>
                    <span id="order-total"><?= $arResult['BASKET_PRICE_FORMAT']; ?></span>
                    <? if (!empty($_SESSION["CATALOG_USER_COUPONS"])) : ?>
                        <span><?= $arResult['ORDER_PRICE_FORMAT']; ?></span>
                    <? endif; ?>
                </div>

                <a href="javascript:void(0);" data-order-checkout-submit class="btn btn--transparent" title="Оформить заказ">Оформить заказ</a>

                <div class="order-total__pp">
                    Нажав на кнопку "отправить заказ", я даю свое согласие на
                    обработку персональных данных <a href="/policy/" rel="noopener noreferrer" target="_blank" title="Политика в отношении обработки персональных данных">в соответствии с указанными
                        здесь условиями</a>
                </div>
            </div>
        </div>
        <div class="order__main">
            <form action="<?= $page; ?>" id="order-checkout-form" class="b-form disabled">
                <input type="hidden" name="save" value="Y" />
                <input type="hidden" name="LOCATION_CITY" id="LOCATION_CITY"
                       data-location-city="order" onchange="OrderCheckout.updateForm();"
                       value="<?= $arResult['LOCATION_CITY']; ?>" />
                <input type="hidden" name="LOCATION_KLADR" id="LOCATION_KLADR"
                       data-location-kladr="order" value="<?= $arResult['LOCATION_KLADR']; ?>" />

                <div class="order__top">
                    <span><?= $arContragent['UF_NAME']; ?></span>
                    <span><?= $strName; ?></span>
                    <span><?= $phoneContragent; ?></span>
                </div>

                <div class="order__content">
                    <div class="title-2">Доставка</div>

                    <div class="order__delivery">
                        <? if (!empty($arResult['PICKUP_LIST'])) { ?>
                            <div class="b-checkbox b-checkbox--radio">
                                <label class="b-checkbox__label">
                                    <input type="radio" name="DELIVERY_ID" value="<?= OrderRepository::DELIVERY_PICKUP; ?>"
                                           class="b-checkbox__input" data-order-checkout-change
                                        <?= ($arResult['DELIVERY_ID']==OrderRepository::DELIVERY_PICKUP)?'checked':''; ?>>
                                    <span class="b-checkbox__box"></span>
                                    <span class="b-checkbox__text">Самовывоз</span>
                                </label>
                                <span class="b-checkbox__subtext">Доступен только для Москвы и МО</span>
                            </div>
                        <? } ?>

                        <? if (!empty($arResult['DELIVERIES'][OrderRepository::DELIVERY_COURIER])) { ?>
                            <div class="b-checkbox b-checkbox--radio <?= $arResult['BASKET_PRICE'] < 5000 ? 'disabled' : '' ?>">
                                <label class="b-checkbox__label">
                                    <input type="radio" name="DELIVERY_ID" value="<?= OrderRepository::DELIVERY_COURIER; ?>"
                                           class="b-checkbox__input" data-order-checkout-change
                                        <?= ($arResult['DELIVERY_ID']==OrderRepository::DELIVERY_COURIER)?'checked':''; ?>>
                                    <span class="b-checkbox__box"></span>
                                    <span class="b-checkbox__text">Собственная курьерская доставка</span>
                                </label>
                                <span class="b-checkbox__subtext">
                                    Собственная курьерская доставка компании. <br>
                                    Для Москвы при заказе от 5000 руб., для МО при заказе от 15000 руб.
                                </span>
                            </div>
                        <? } ?>

                        <div class="b-checkbox b-checkbox--radio">
                            <label class="b-checkbox__label">
                                <input type="radio" name="DELIVERY_ID" value="<?= $component::DELIVERY_TRANSPORT_COMPANY; ?>"
                                       class="b-checkbox__input" data-order-checkout-change
                                    <?= ($arResult['DELIVERY_ID']==$component::DELIVERY_TRANSPORT_COMPANY)?'checked':''; ?>>
                                <span class="b-checkbox__box"></span>
                                <span class="b-checkbox__text">Доставка транспортной компанией</span>
                            </label>
                        </div>
                    </div>
                </div>


                <?
                /**
                 * самовывоз
                 */
                switch ($arResult['DELIVERY_ID']) {
                    case OrderRepository::DELIVERY_PICKUP:
                        ?>
                        <div class="order__inner">
                            <div class="order__column">
                                <div class="order__label">Выберите адрес пункта самовывоза:</div>

                                <? if (!empty($arResult['PICKUP_LIST'])) { ?>
                                    <select name="PICKUP" id="PICKUP">
                                        <?
                                        $valPickup = $component->getPropValueByCode('PICKUP');
                                        foreach ($arResult['PICKUP_LIST'] as $pickup) { ?>
                                            <option value="<?= $pickup['ID']; ?>"
                                                <?= ($valPickup==$pickup['UF_ADDRESS'])?'selected':''; ?>><?= $pickup['UF_ADDRESS']; ?></option>
                                        <? }  ?>
                                    </select>
                                <? } ?>

                                <div class="order__label">Дата доставки *:</div>
                                <div class="b-form__item" data-f-item data-datepicker-delivery>
                                    <span class="b-form__label" data-f-label>дд.мм.гггг</span>
                                    <input type="text" name="DATE_DELIVERY" data-f-field data-mask="date"
                                           data-form-field-date data-required="Y" autocomplete="off"
                                           value="<?= $component->getPropValueByCode('DATE_DELIVERY'); ?>">
                                    <span class="b-form__text alert alert--error hidden" data-form-error="">Некорректно заполнено поле</span>
                                </div>
                                <span class="basket-item__text">Если заказ сформирован до 14 часов, то заявка на доставку <br>
                                    будет сформирована в течение текущего дня, если позже 14 часов, <br>то на следующий день</span>

                                <div class="order__label">Время доставки:</div>
                                <div class="order__time">
                                    <div class="b-form__item" data-f-item>
                                        <span class="b-form__label" data-f-label>с</span>
                                        <input type="text" name="DELIVERY_TIME_1" data-f-field
                                               data-mask="time"
                                               value="<?= $component->getPropValueByCode('DELIVERY_TIME_1'); ?>"
                                               autocomplete="off">
                                    </div>
                                    <span></span>
                                    <div class="b-form__item" data-f-item>
                                        <span class="b-form__label" data-f-label>по</span>
                                        <input type="text" name="DELIVERY_TIME_2" data-f-field
                                               data-mask="time"
                                               value="<?= $component->getPropValueByCode('DELIVERY_TIME_2'); ?>"
                                               autocomplete="off">
                                    </div>
                                </div>

                                <div class="b-form__item b-form__item--textarea" data-f-item>
                                    <span class="b-form__label" data-f-label>Комментарий к заказу</span>
                                    <textarea data-f-field="" name="COMMENT"></textarea>
                                </div>
                            </div>
                        </div>
                        <?
                        break;

                    /**
                     * Собственная курьерская доставка
                     */
                    case OrderRepository::DELIVERY_COURIER:
                        ?>
                        <div class="order__label">Укажите грузополучателя *:</div>
                        <div class="order__inner order__inner-after-label">
                            <? if (!empty($arResult['CONSIGNEES_LIST'])) { ?>
                                <div class="order__column">
                                    <?
                                    $val = $component->getPropValueByCode('CONSIGNEES_LIST');
                                    ?>
                                    <select name="CONSIGNEES_LIST" id="CONSIGNEES_LIST" data-required="Y">
                                        <? foreach ($arResult['CONSIGNEES_LIST'] as $value) { ?>
                                            <option value="<?= $value['ID']; ?>"
                                                <?= ($val==$value['UF_NAME'])?'selected':''; ?>><?= $value['UF_NAME']; ?></option>
                                        <? } ?>
                                    </select>
                                </div>

                                <span>Или</span>
                            <? } ?>
                            <div class="order__column">
                                <div class="b-form__item" data-f-item>
                                    <? if (!empty($arResult['CONSIGNEES_LIST'])) { ?>
                                        <span class="b-form__label" data-f-label>Укажите грузополучателя вручную</span>
                                    <? } else { ?>
                                        <span class="b-form__label" data-f-label>Укажите грузополучателя</span>
                                    <? } ?>
                                    <input type="text" autocomplete="off"
                                           value="<?= htmlspecialchars($component->getRequestByCode('CONSIGNEES_HANDLE')); ?>"
                                        <?php if (empty($arResult['CONSIGNEES_LIST'])) { ?>
                                            data-required="Y"
                                        <?php } ?>
                                           data-f-field name="CONSIGNEES_HANDLE" id="CONSIGNEES_HANDLE">
                                    <span class="b-form__text alert alert--error hidden" data-form-error="">Некорректно заполнено поле</span>
                                </div>
                            </div>
                        </div>

                        <div class="order__label">Укажите адрес доставки:</div>
                        <div class="order__inner order__inner-after-label">
                            <? if (!empty($arResult['SHIP_ADDRESS_LIST'])) { ?>
                                <div class="order__column">
                                    <select name="SHIP_ADDRESS_LIST" id="SHIP_ADDRESS_LIST" onchange="OrderCheckout.updateForm();">
                                        <?
                                        $val = $component->getPropValueByCode('SHIP_ADDRESS_LIST');
                                        ?>
                                        <? foreach ($arResult['SHIP_ADDRESS_LIST'] as $address) { ?>
                                            <option value="<?= $address['ID']; ?>"
                                                <?= ($val==$address['UF_NAME'])?'selected':''; ?>><?= $address['UF_NAME']; ?></option>
                                        <? }  ?>
                                    </select>
                                </div>
                                <span>Или</span>
                            <? } ?>

                            <div class="order__column">
                                <div class="b-form__item" data-f-item>
                                    <span class="b-form__label" data-f-label>Укажите адрес вручную</span>
                                    <input type="text" data-suggestion="address" autocomplete="off"
                                           value="<?= $component->getRequestByCode('SHIP_ADDRESS_LIST_HANDLE')?>"
                                           data-f-field name="SHIP_ADDRESS_LIST_HANDLE" id="SHIP_ADDRESS_LIST_HANDLE">
                                </div>
                            </div>
                        </div>

                        <div class="order__inner order__inner--additional">
                            <div class="order__column">
                                <div class="order__label">Дата доставки *:</div>
                                <div class="b-form__item" data-f-item data-datepicker-delivery>
                                    <span class="b-form__label" data-f-label>дд.мм.гггг</span>
                                    <input type="text" name="DATE_DELIVERY" data-f-field data-mask="date" autocomplete="off"
                                           data-form-field-date data-required="Y"
                                           value="<?= $component->getPropValueByCode('DATE_DELIVERY'); ?>">
                                    <span class="b-form__text alert alert--error hidden" data-form-error="">Некорректно заполнено поле</span>
                                </div>
                                <span class="basket-item__text">Если заказ сформирован до 14 часов, то заявка на доставку <br>
                                    будет сформирована в течение текущего дня, если позже 14 часов, <br>то на следующий день</span>

                                <div class="order__label">Время доставки:</div>
                                <div class="order__time">
                                    <div class="b-form__item" data-f-item>
                                        <span class="b-form__label" data-f-label>с</span>
                                        <input type="text" name="DELIVERY_TIME_1" data-f-field
                                               data-mask="time"
                                               value="<?= $component->getPropValueByCode('DELIVERY_TIME_1'); ?>"
                                               autocomplete="off">
                                    </div>
                                    <span></span>
                                    <div class="b-form__item" data-f-item>
                                        <span class="b-form__label" data-f-label>по</span>
                                        <input type="text" name="DELIVERY_TIME_2" data-f-field
                                               data-mask="time"
                                               value="<?= $component->getPropValueByCode('DELIVERY_TIME_2'); ?>"
                                               autocomplete="off">
                                    </div>
                                </div>

                                <div class="b-form__item b-form__item--textarea" data-f-item>
                                    <span class="b-form__label" data-f-label>Комментарий к заказу</span>
                                    <textarea data-f-field="" name="COMMENT"></textarea>
                                </div>
                            </div>

                            <div class="order__column">
                                <div class="order__radio">
                                    <div class="order__label">Не печатать цены:</div>
                                    <?
                                    $NO_PRINT_PRICE = $component->getPropValueByCode('NO_PRINT_PRICE');
                                    ?>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="NO_PRINT_PRICE" name="NO_PRINT_PRICE"
                                                   class="b-checkbox__input" value="Y"
                                                <?= ($NO_PRINT_PRICE=='Y')?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Да</span>
                                        </label>
                                    </div>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="NO_PRINT_PRICE" name="NO_PRINT_PRICE"
                                                   class="b-checkbox__input" value="N"
                                                <?= ($NO_PRINT_PRICE=='N'||!$NO_PRINT_PRICE)?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Нет</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="order__radio">
                                    <?
                                    $PALLET_BOARD = $component->getPropValueByCode('PALLET_BOARD');
                                    ?>
                                    <div class="order__label">Паллетный борт:</div>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="NO_PRINT_PRICE" name="PALLET_BOARD"
                                                   class="b-checkbox__input" value="Y"
                                                <?= ($PALLET_BOARD=='Y')?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Да</span>
                                        </label>
                                    </div>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="NO_PRINT_PRICE" name="PALLET_BOARD"
                                                   class="b-checkbox__input" value="N"
                                                <?= ($PALLET_BOARD=='N'||!$PALLET_BOARD)?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Нет</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="order__radio">
                                    <?
                                    $PALLET_REQUIRED = $component->getPropValueByCode('PALLET_REQUIRED');
                                    ?>
                                    <div class="order__label">Паллетировать обязательно:</div>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="PALLET_REQUIRED" name="PALLET_REQUIRED"
                                                   class="b-checkbox__input" value="Y"
                                                <?= ($PALLET_REQUIRED=='Y')?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Да</span>
                                        </label>
                                    </div>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="PALLET_REQUIRED" name="PALLET_REQUIRED"
                                                   class="b-checkbox__input" value="N"
                                                <?= ($PALLET_REQUIRED=='N'||!$PALLET_REQUIRED)?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Нет</span>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <?
                        break;

                    case $component::DELIVERY_TRANSPORT_COMPANY:
                        ?>
                        <div class="order__inner">
                            <div class="order__column">
                                <div class="order__label">Выберите транспортную компанию:</div>
                                <select name="DELIVERY_ID_TRANSPORT" id="DELIVERY_ID_TRANSPORT" onchange="OrderCheckout.updateForm();">
                                    <option value="<?= OrderRepository::DELIVERY_PEK; ?>"
                                        <?=$arResult['DELIVERY_ID_TRANSPORT']==OrderRepository::DELIVERY_PEK?'selected':''?>>Транспортной компанией ПЭК</option>
                                    <option value="<?= OrderRepository::DELIVERY_MAIN_DELIVERY; ?>"
                                        <?=$arResult['DELIVERY_ID_TRANSPORT']==OrderRepository::DELIVERY_MAIN_DELIVERY?'selected':''?>>Транспортной компанией Главдоставка</option>
                                    <option value="<?= OrderRepository::DELIVERY_DELINDEV; ?>"
                                        <?=$arResult['DELIVERY_ID_TRANSPORT']==OrderRepository::DELIVERY_DELINDEV?'selected':''?>>Транспортной компанией Деловые линии</option>
                                    <option value="<?= OrderRepository::DELIVERY_OTHER; ?>"
                                        <?=$arResult['DELIVERY_ID_TRANSPORT']==OrderRepository::DELIVERY_OTHER?'selected':''?>>Другой транспортной компанией</option>
                                </select>
                            </div>
                        </div>

                        <div class="order__label">Укажите грузополучателя *:</div>
                        <div class="order__inner order__inner-after-label">
                            <? if (!empty($arResult['CONSIGNEES_LIST'])) { ?>
                                <div class="order__column">
                                    <?
                                    $val = $component->getPropValueByCode('CONSIGNEES_LIST');
                                    ?>
                                    <select name="CONSIGNEES_LIST" id="CONSIGNEES_LIST" data-required="Y">
                                        <? foreach ($arResult['CONSIGNEES_LIST'] as $value) { ?>
                                            <option value="<?= $value['ID']; ?>"
                                                <?= ($val==$value['UF_NAME'])?'selected':''; ?>><?= $value['UF_NAME']; ?></option>
                                        <? } ?>
                                    </select>
                                </div>
                                <span>Или</span>
                            <? } ?>

                            <div class="order__column">
                                <div class="b-form__item" data-f-item>
                                    <? if (!empty($arResult['CONSIGNEES_LIST'])) { ?>
                                        <span class="b-form__label" data-f-label>Укажите грузополучателя вручную</span>
                                    <? } else { ?>
                                        <span class="b-form__label" data-f-label>Укажите грузополучателя</span>
                                    <? } ?>
                                    <input type="text" autocomplete="off"
                                           value="<?= htmlspecialchars($component->getRequestByCode('CONSIGNEES_HANDLE')); ?>"
                                        <?php if (empty($arResult['CONSIGNEES_LIST'])) { ?>
                                            data-required="Y"
                                        <?php } ?>
                                           data-f-field name="CONSIGNEES_HANDLE" id="CONSIGNEES_HANDLE">
                                    <span class="b-form__text alert alert--error hidden" data-form-error="">Некорректно заполнено поле</span>
                                </div>
                            </div>
                        </div>

                        <div class="order__label">Укажите адрес доставки:</div>
                        <div class="order__inner order__inner-after-label">
                            <? if (!empty($arResult['SHIP_ADDRESS_LIST'])) { ?>
                                <div class="order__column">
                                    <select name="SHIP_ADDRESS_LIST" id="SHIP_ADDRESS_LIST" onchange="OrderCheckout.updateForm();">
                                        <?
                                        $val = $component->getPropValueByCode('SHIP_ADDRESS_LIST');
                                        ?>
                                        <? foreach ($arResult['SHIP_ADDRESS_LIST'] as $address) { ?>
                                            <option value="<?= $address['ID']; ?>"
                                                <?= ($val==$address['UF_NAME'])?'selected':''; ?>><?= $address['UF_NAME']; ?></option>
                                        <? }  ?>
                                    </select>
                                </div>
                                <span>Или</span>
                            <? } ?>

                            <div class="order__column">
                                <div class="b-form__item" data-f-item>
                                    <span class="b-form__label" data-f-label>Укажите адрес вручную</span>
                                    <input type="text" data-suggestion="address" autocomplete="off"
                                           value="<?= $component->getRequestByCode('SHIP_ADDRESS_LIST_HANDLE')?>"
                                           data-f-field name="SHIP_ADDRESS_LIST_HANDLE" id="SHIP_ADDRESS_LIST_HANDLE">
                                </div>
                            </div>
                        </div>

                        <div class="order__inner order__inner--additional">
                            <div class="order__column">

                                <div class="order__label">Дата доставки *:</div>
                                <div class="b-form__item" data-f-item data-datepicker-delivery>
                                    <span class="b-form__label" data-f-label>дд.мм.гггг</span>
                                    <input type="text" name="DATE_DELIVERY" data-f-field data-mask="date" autocomplete="off"
                                           data-form-field-date data-required="Y"
                                           value="<?= $component->getPropValueByCode('DATE_DELIVERY'); ?>">
                                    <span class="b-form__text alert alert--error hidden" data-form-error="">Некорректно заполнено поле</span>
                                </div>
                                <span class="basket-item__text">Если заказ сформирован до 14 часов, то заявка на доставку <br>
                                    будет сформирована в течение текущего дня, если позже 14 часов, <br>то на следующий день</span>

                                <div class="order__label">Время доставки:</div>
                                <div class="order__time">
                                    <div class="b-form__item" data-f-item>
                                        <span class="b-form__label" data-f-label>с</span>
                                        <input type="text" name="DELIVERY_TIME_1" data-f-field
                                               data-mask="time"
                                               value="<?= $component->getPropValueByCode('DELIVERY_TIME_1'); ?>"
                                               autocomplete="off">
                                    </div>
                                    <span></span>
                                    <div class="b-form__item" data-f-item>
                                        <span class="b-form__label" data-f-label>по</span>
                                        <input type="text" name="DELIVERY_TIME_2" data-f-field
                                               data-mask="time"
                                               value="<?= $component->getPropValueByCode('DELIVERY_TIME_2'); ?>"
                                               autocomplete="off">
                                    </div>
                                </div>

                                <div class="b-form__item b-form__item--textarea" data-f-item>
                                    <span class="b-form__label" data-f-label>Комментарий к заказу</span>
                                    <textarea data-f-field="" name="COMMENT"></textarea>
                                </div>
                            </div>

                            <div class="order__column">
                                <div class="order__radio">
                                    <div class="order__label">Не печатать цены:</div>
                                    <?
                                    $NO_PRINT_PRICE = $component->getPropValueByCode('NO_PRINT_PRICE');
                                    ?>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="NO_PRINT_PRICE1" name="NO_PRINT_PRICE"
                                                   class="b-checkbox__input" value="Y"
                                                <?= ($NO_PRINT_PRICE=='Y')?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Да</span>
                                        </label>
                                    </div>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="NO_PRINT_PRICE2" name="NO_PRINT_PRICE"
                                                   class="b-checkbox__input" value="N"
                                                <?= ($NO_PRINT_PRICE=='N'||!$NO_PRINT_PRICE)?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Нет</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="order__radio">
                                    <div class="order__label">Обрешетка:</div>
                                    <? $CRATE = $component->getPropValueByCode('CRATE'); ?>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="CRATE1" name="CRATE"
                                                   class="b-checkbox__input" value="Y"
                                                <?= ($CRATE=='Y')?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Да</span>
                                        </label>
                                    </div>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="CRATE2" name="CRATE"
                                                   class="b-checkbox__input" value="N"
                                                <?= ($CRATE=='N'||!$CRATE)?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Нет</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="order__radio">
                                    <div class="order__label">Разгрузка:</div>
                                    <? $UNLOADING = $component->getPropValueByCode('UNLOADING'); ?>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="UNLOADING1" name="UNLOADING"
                                                   class="b-checkbox__input" value="Y"
                                                <?= ($UNLOADING=='Y')?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Да</span>
                                        </label>
                                    </div>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="UNLOADING2" name="UNLOADING"
                                                   class="b-checkbox__input" value="N"
                                                <?= ($UNLOADING=='N'||!$UNLOADING)?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Нет</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="order__radio">
                                    <div class="order__label">Доставка до дверей:</div>
                                    <? $DELIVERY_TO_DOOR = $component->getPropValueByCode('DELIVERY_TO_DOOR'); ?>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="DELIVERY_TO_DOOR1" name="DELIVERY_TO_DOOR"
                                                   class="b-checkbox__input" value="Y"
                                                <?= ($DELIVERY_TO_DOOR=='Y')?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Да</span>
                                        </label>
                                    </div>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="DELIVERY_TO_DOOR2" name="DELIVERY_TO_DOOR"
                                                   class="b-checkbox__input" value="N"
                                                <?= ($DELIVERY_TO_DOOR=='N'||!$DELIVERY_TO_DOOR)?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Нет</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="order__radio">
                                    <?
                                    $PALLET_BOARD = $component->getPropValueByCode('PALLET_BOARD');
                                    ?>
                                    <div class="order__label">Паллетный борт:</div>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="NO_PRINT_PRICE1" name="PALLET_BOARD"
                                                   class="b-checkbox__input" value="Y"
                                                <?= ($PALLET_BOARD=='Y')?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Да</span>
                                        </label>
                                    </div>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="NO_PRINT_PRICE2" name="PALLET_BOARD"
                                                   class="b-checkbox__input" value="N"
                                                <?= ($PALLET_BOARD=='N'||!$PALLET_BOARD)?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Нет</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="order__radio">
                                    <?
                                    $PALLET_REQUIRED = $component->getPropValueByCode('PALLET_REQUIRED');
                                    ?>
                                    <div class="order__label">Паллетировать обязательно:</div>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="PALLET_REQUIRED1" name="PALLET_REQUIRED"
                                                   class="b-checkbox__input" value="Y"
                                                <?= ($PALLET_REQUIRED=='Y')?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Да</span>
                                        </label>
                                    </div>
                                    <div class="b-checkbox b-checkbox--radio">
                                        <label class="b-checkbox__label">
                                            <input type="radio" id="PALLET_REQUIRED2" name="PALLET_REQUIRED"
                                                   class="b-checkbox__input" value="N"
                                                <?= ($PALLET_REQUIRED=='N'||!$PALLET_REQUIRED)?'checked':''; ?>>
                                            <span class="b-checkbox__box"></span>
                                            <span class="b-checkbox__text">Нет</span>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <?
                        break;
                }
                ?>

                <div class="title-2">Товары в заказе</div>
                <div class="order__t">
                    <div class="basket-item basket-item--top">
                        <div class="basket-item__description">Наименование</div>
                        <div class="basket-item__price">Цена</div>
                        <div class="basket-item__price">Количество</div>
                        <div class="basket-item__price">Сумма</div>
                    </div>

                    <? foreach ($arResult['BASKET_ITEMS'] as $item) { ?>
                        <div class="basket-item">
                            <div class="basket-item__description">
                                <a href="<?= $item['DETAIL_PAGE_URL']; ?>" target="_blank" title="<?= $item['NAME']; ?>"><span class="basket-item__title"><?= $item['NAME']; ?></span></a>

                                <div class="basket-item__article">
                                    Артикул:&nbsp;&nbsp;<?= $item['PROPERTIES']['CML2_ARTICLE']['VALUE']; ?>
                                </div>
                            </div>

                            <div class="basket-item__price">
                                <div class="basket-item__t">Цена</div>
                                <span><?= $item['BASKET']['PRICE']; ?> ₽</span>
                                <div class="basket-item__text">цена за 1 шт</div>
                            </div>
                            <div class="basket-item__price">
                                <div class="basket-item__t">Цена</div>
                                <span><?= $item['BASKET']['QUANTITY']; ?> шт</span>
                            </div>
                            <div class="basket-item__price">
                                <div class="basket-item__t">Итого</div>
                                <span><?= $item['BASKET']['FINAL_PRICE']; ?> ₽</span>
                            </div>
                        </div>
                    <? } ?>

                    <? foreach ($arResult['BASKET_ITEMS'] as $item)
                    {
                        $arQuantity[] = $item["BASKET"]["QUANTITY"];
                        $arFinalPrise[] = $item["BASKET"]["FINAL_PRICE"];
                    } ?>
                    <div class="basket-item">
                        <div class="basket-item__description">
                            <span><h2>Итого:</h2></span>
                        </div>

                        <div class="basket-item__price">
                        </div>
                        <div class="basket-item__price">
                            <?$res=array_sum($arQuantity);?>

                            <span><h3><?= $res; ?> шт</h3></span>
                        </div>
                        <div class="basket-item__price">
                            <?$res=array_sum($arFinalPrise);?>

                            <span>
                                <h3 id="total-price-table"><?= $res; ?> ₽</h3>
                                <? if (!empty($_SESSION["CATALOG_USER_COUPONS"])) : ?>
                                    <h3><?= $arResult['ORDER_PRICE']; ?> ₽</h3>
                                <? endif; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="order__sidebar" data-fix-sidebar>
            <div class="order-total" data-fix-item>

                <div class="title-2">Ваш заказ</div>

                <div class="order-total__item order-total__item--r">
                    <span>Товаров на сумму:</span>
                    <span><?= $arResult['BASKET_PRICE_FORMAT']; ?></span>
                </div>

                <div class="order-total__item order-total__item--r">
                    <span>Доставка:</span>
                    <span><?= $arResult['DELIVERY_PRICE_FORMAT']; ?></span>
                </div>

                <div class="order-total__item">
                    <span>Общий вес:</span>
                    <span><?= round($arResult['VES_BRUTTO'], 1); ?> кг.</span>
                </div>

                <div class="order-total__item">
                    <span>Объем:</span>
                    <span><?= round($arResult['OBEM'], 2); ?> м <sup>3</sup></span>
                </div>

                <div class="basket-total-info__left">
                    <div class="basket-total__sum basket-total__sum--small basket-total__sum--available">
                        <span>В наличии:</span>
                        <span><?=$availableSum?> ₽</span>
                    </div>

                    <div class="basket-total__sum basket-total__sum--small basket-total__sum--not-available">
                        <span>Нет в наличии:</span>
                        <span><?=$notAvailableSum?> ₽</span>
                    </div>
                </div>

                <div class="order-total__item order-total__item--sum">
                    <span>Итого:</span>
                    <span id="total-price"><?= $arResult['BASKET_PRICE_FORMAT']; ?></span>
                    <? if (!empty($_SESSION["CATALOG_USER_COUPONS"])) : ?>
                        <span><?= $arResult['ORDER_PRICE_FORMAT']; ?></span>
                    <? endif; ?>
                </div>

                <a href="javascript:void(0);" data-order-checkout-submit class="btn btn--transparent" title="Оформить заказ">Оформить заказ</a>

                <div class="order-total__pp">
                    Нажав на кнопку "отправить заказ", я даю свое согласие на
                    обработку персональных данных <a href="/policy/" title="Политика в отношении обработки персональных данных" rel="noopener noreferrer" target="_blank" class="link">в соответствии с указанными
                        здесь условиями</a>
                </div>
            </div>
        </div>

    </div>

<? if (!empty($_SESSION["CATALOG_USER_COUPONS"])) : ?>
<script>
    document.querySelector('#order-total').classList.add('order-price');
    document.querySelector('#order-total + span').classList.add('order-price__discount');

    document.querySelector('#total-price-table').classList.add('total-price-table')
    document.querySelector('#total-price-table + h3').classList.add('total-price-table__discount');

    document.querySelector('#total-price').classList.add('total-price');
    document.querySelector('#total-price + span').classList.add('total-price__discount');
</script>
<? endif; ?>

<?if ($component->isAjax !== true){?>
    <script type="text/javascript">
        if (typeof BX.Currency === 'object') {
            BX.Currency.setCurrencyFormat('RUB', <? echo CUtil::PhpToJSObject($arResult['CURRENCY'], false, true); ?>);
        }

        document.addEventListener('App.Ready', function (e) {
            // signedParameters - перечень ключей параметров компонента
            OrderCheckout.init(<?=json_encode([
                'signedParameters' => $component->getSignedParameters(),
                'wrapId' => '#order-checkout-wrap',
                'currency' => $arResult['CURRENCY'],
                'isAjax' => $component->isAjax,
            ])?>);
        }, false);
    </script>
<?}?>