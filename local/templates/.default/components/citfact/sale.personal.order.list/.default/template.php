<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset,
    Citfact\SiteCore\Shipment\ShipmentStatus;

Loc::loadMessages(__FILE__);
$shipmentStatus = new ShipmentStatus();

$bxajaxid = CAjax::GetComponentID($component->__name, $component->__template->__name, $component->arParams['AJAX_OPTION_ADDITIONAL']);

if (!empty($arResult['ERRORS']['FATAL'])) {
    foreach ($arResult['ERRORS']['FATAL'] as $error) {
        ShowError($error);
    }
    $component = $this->__component;
    if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED])) {
        $APPLICATION->AuthForm('', false, false, 'N', false);
    }

} else { ?>
    <div class="lk-orders">
        <form class="lk-orders__top b-form" name="citfact_personal_order_form" id="filter_orders">
            <div class="b-form__item b-form__item--select" data-f-item>
                <span class="b-form__label" data-f-label>Вид заказа</span>
                <select name="type" id="type" class="select--white" data-f-field data-select-orders-sort
                        data-url="<?= $APPLICATION->GetCurPageParam(); ?>">
                    <option value="online">Online заказ</option>
                    <option value="offline">Offline заказ</option>
                    <option value="all">Все заказы</option>
                </select>
            </div>
            <div class="b-form__item b-form__item--select" data-f-item>
                <span class="b-form__label" data-f-label>Поиск заказа</span>
                <select name="filter" id="filter" class="select--white" data-f-field>
                    <option value="id">Номер заказа</option>
                    <option value="date">Дата создания заказа</option>
                </select>
            </div>
            <div class="lk-orders__search" id="input_id">
                <div class="b-form__item" data-f-item>
                    <span class="b-form__label" data-f-label>Введите номер заказа</span>

                    <input type="text" name="value_id" data-f-field autocomplete="off" data-order-feald>
                    <button type="submit" name="ACTION_SEARCH" value="search">
                        <svg class='i-icon'>
                            <use xlink:href='#icon-search'/>
                        </svg>
                    </button>
                    <button type="submit" name="ACTION_CLEAR" value="clear" class="clear" id="clear">
                        <span class="plus plus--cross"></span>
                    </button>

                    <span class="b-form__text"></span>
                </div>
            </div>
            <div class="lk-orders__search hidden" id="input_date">
                <div class="b-form__item" data-f-item data-datepicker>
                    <span class="b-form__label" data-f-label>Введите дату заказа</span>

                    <input type="text" name="value_date" data-f-field data-mask="date" autocomplete="off" data-order-feald>
                    <button type="submit" name="ACTION_CLEAR" value="clear" class="clear" id="clear">
                        <span class="plus plus--cross"></span>
                    </button>
                    <button type="submit" name="ACTION_SEARCH" value="search">
                        <svg class='i-icon'>
                            <use xlink:href='#icon-search'/>
                        </svg>
                    </button>

                <span class="b-form__text">

                </span>
                </div>
            </div>
        </form>


        <div class="lk__section" id="block_<?=$bxajaxid?>">
            <div class="lk__section-head">
				<? include $_SERVER['DOCUMENT_ROOT'] . "/local/include/areas/order/tabs-head.php"; ?>
            </div>
            <div data-update-filter-block>
            <!-- orders !-->

            <? if ($arResult['ORDERS']) { ?>
                <? foreach ($arResult['ORDERS'] as $key => $order) { ?>
                    <div class="lk-orders-i" data-toggle-wrap>
                        <div
                            class="lk-orders-i__header"<? if ($order['PAYMENT'] || $order['SHIPMENT']): ?> data-toggle-btn<? endif; ?>>
                            <div>
                                <div>
                                    <? if (true === $order['IS_OFFLINE']): ?>
                                        <span class="red">Offline</span>&nbsp;/&nbsp;
                                    <? else: ?>
                                        <span class="green">Online</span>&nbsp;/&nbsp;
                                    <? endif; ?>
                                    <?= Loc::getMessage('SPOL_TPL_ORDER') ?>
                                    <?if($order['ORDER']['1C_NUMBER']){?>
                                    <?= Loc::getMessage('SPOL_TPL_NUMBER_SIGN') . $order['ORDER']['1C_NUMBER'] ?>
                                    <?} else{?>
                                        <?= Loc::getMessage('SPOL_TPL_NUMBER_SIGN') . $order['ORDER']['ID'] ?>
                                    <?}?>
                                    <?= Loc::getMessage('SPOL_TPL_FROM_DATE') ?>
                                    <?= $order['ORDER']['DATE_INSERT']->format($arParams['ACTIVE_DATE_FORMAT']) ?>,
                                    <? $count = count($order['BASKET_ITEMS']) % 10;
                                    if ($count == '1') {
                                        echo Loc::getMessage('SPOL_TPL_SELECT');
                                    } else {
                                        echo Loc::getMessage('SPOL_TPL_SELECTS');
                                    }
                                    ?>
                                    <?= count($order['BASKET_ITEMS']); ?>
                                    <?
                                    if ($count == '1') {
                                        echo Loc::getMessage('SPOL_TPL_GOOD');
                                    } elseif ($count >= '2' && $count <= '4') {
                                        echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
                                    } else {
                                        echo Loc::getMessage('SPOL_TPL_GOODS');
                                    }
                                    ?>
                                    <?= Loc::getMessage('SPOL_TPL_SUMOF') ?>
                                    <?= $order['ORDER']['FORMATED_PRICE'] ?>
                                </div>
                                <div>
                                    <? if ($order['ORDER']['CANCELED'] === 'Y') {
                                        echo '<span class="red">' . Loc::getMessage('SPOL_TPL_ORDER_CANCELED') . '</span>';
                                    } elseif ($arResult['INFO']['STATUS'][$order['ORDER']['STATUS_ID']]['ID'] === 'N') {
                                        echo '<span class="yellow">' . $arResult['INFO']['STATUS'][$order['ORDER']['STATUS_ID']]['NAME'] . '</span>';
                                    } else {
                                        echo '<span class="green">' . $arResult['INFO']['STATUS'][$order['ORDER']['STATUS_ID']]['NAME'] . '</span>';
                                    } ?>
                                    &nbsp;<?= $order['ORDER']['DATE_STATUS']->format('d.m.Y') ?>
                                </div>
                            </div>
                            <? if (in_array($order['ORDER']['USER_ID'], $arResult['USERS_MANAGER'])) { ?>
                                <div class="lk-orders-i__from">
                                    Заказ инициирован менеджером
                                </div>
                            <? } ?>
                        </div>
                        <div class="lk-orders-i__content">
                            <div class="lk-orders-i__detail hidden" data-toggle-list>
                                <? if ($order['PAYMENT']): ?>
                                    <div class="lk-orders-i__title"><?= Loc::getMessage('SPOL_TPL_PAYMENT') ?></div>
                                <? endif; ?>
                                <?
                                $showDelimeter = false;
                                foreach ($order['PAYMENT'] as $payment) {
                                    if ($order['ORDER']['LOCK_CHANGE_PAYSYSTEM'] !== 'Y') {
                                        $paymentChangeData[$payment['ACCOUNT_NUMBER']] = array(
                                            "order" => htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']),
                                            "payment" => htmlspecialcharsbx($payment['ACCOUNT_NUMBER']),
                                            "allow_inner" => $arParams['ALLOW_INNER'],
                                            "refresh_prices" => $arParams['REFRESH_PRICES'],
                                            "path_to_payment" => $arParams['PATH_TO_PAYMENT'],
                                            "only_inner_full" => $arParams['ONLY_INNER_FULL']
                                        );
                                    }
                                    ?>
                                    <div class="lk-orders-i__status">
                                        <div class="title-3">
                                            <?
                                            $paymentSubTitle = Loc::getMessage('SPOL_TPL_BILL') . " " . Loc::getMessage('SPOL_TPL_NUMBER_SIGN') . htmlspecialcharsbx($payment['ACCOUNT_NUMBER']);
                                            if (isset($payment['DATE_BILL'])) {
                                                $paymentSubTitle .= " " . Loc::getMessage('SPOL_TPL_FROM_DATE') . " " . $payment['DATE_BILL']->format($arParams['ACTIVE_DATE_FORMAT']);
                                            }
                                            $paymentSubTitle .= ", ";
                                            echo $paymentSubTitle;
                                            ?><?= $payment['PAY_SYSTEM_NAME'] ?>
                                        </div>
                                        <?
                                        if ($payment['PAID'] === 'Y') {
                                            ?>
                                            <span class="red"><?= Loc::getMessage('SPOL_TPL_PAID') ?></span>
                                            <?
                                        } elseif ($order['ORDER']['IS_ALLOW_PAY'] == 'N') {
                                            ?>
                                            <span class="red"><?= Loc::getMessage('SPOL_TPL_RESTRICTED_PAID') ?></span>
                                            <?
                                        } else {
                                            ?>
                                            <span class="red"><?= Loc::getMessage('SPOL_TPL_NOTPAID') ?></span>
                                            <?
                                        }
                                        ?>
                                    </div>
                                    <p><?= Loc::getMessage('SPOL_TPL_SUM_TO_PAID') ?>
                                        : <?= $payment['FORMATED_SUM'] ?></p>
                                    <?
                                    if (count($arResult['ORDER_FILES'])) {
                                        ?>
                                        <? foreach ($arResult['ORDER_FILES'] as $file) { ?>
                                            <a href="<?= $file['FILE']['SRC']; ?>" class="btn btn--grey" title="Скачать счет" download>Скачать
                                                счет</a>
                                        <? } ?>
                                        <?
                                    }
                                } ?>
                                <? if ($order['SHIPMENT']): ?>
                                    <div class="lk-orders-i__title"><?= Loc::getMessage('SPOL_TPL_DELIVERY') ?></div>
                                <? endif; ?>
                                <? $showDelimeter = false;
                                foreach ($order['SHIPMENT'] as $shipment) {
                                    if (empty($shipment)) {
                                        continue;
                                    }
                                    $firstShipment = $shipment[0];
                                    ?>
                                    <div class="lk-orders-i__status">
                                        <? // Отгрузка №?>
                                        <div class="title-3">
                                            <?= Loc::getMessage('SPOL_TPL_LOAD') ?>
                                            <?
                                            $shipmentSubTitle = Loc::getMessage('SPOL_TPL_NUMBER_SIGN') . htmlspecialcharsbx($firstShipment['UF_NOMER1S']);
                                            if ($shipment['DATE_DEDUCTED']) {
                                                $shipmentSubTitle .= " " . Loc::getMessage('SPOL_TPL_FROM_DATE') . " " . $shipment['DATE_DEDUCTED']->format('d.m.Y');
                                            }
                                            echo $shipmentSubTitle;
                                            ?>
                                        </div>
                                        <? //Статус оплаты
                                        if ($firstShipment['PAID'] === 'Y') {
                                            ?>
                                            <span class="green"><?= Loc::getMessage('SPOL_TPL_PAID') ?></span>
                                            <?
                                        } elseif ($order['ORDER']['IS_ALLOW_PAY'] == 'N') {
                                            ?>
                                            <span
                                                class="yellow"><?= Loc::getMessage('SPOL_TPL_RESTRICTED_PAID') ?></span>
                                            <?
                                        } else {
                                            ?>
                                            <span class="red"><? //Loc::getMessage('SPOL_TPL_NOTPAID') ?></span>
                                            <?
                                        }
                                        ?>
                                    </div>

                                    <? // Статус отгрузки
                                    if (strlen($firstShipment["UF_STATUS"])) {
                                        $color = $shipmentStatus->getColor(htmlspecialcharsbx($firstShipment['UF_STATUS'])); ?>
                                        <p><?= Loc::getMessage('SPOL_ORDER_SHIPMENT_STATUS'); ?>:&nbsp;
                                            <span
                                                class="<?= $color ?>"><?= htmlspecialcharsbx($firstShipment['UF_STATUS']) ?></span>
                                        </p>
                                    <? } ?>

                                    <? // Адрес доставки
                                    if (!empty($firstShipment['UF_ADRESDOSTAVKI']) && !empty($arResult['ADDRESSES'][$firstShipment['UF_ADRESDOSTAVKI']])) { ?>
                                        <p><?= Loc::getMessage('SPOL_ORDER_ADDRESSES'); ?>:&nbsp;
                                            <span>
                                                <?= $arResult['ADDRESSES'][$firstShipment['UF_ADRESDOSTAVKI']]['UF_NAME']; ?>
                                            </span>
                                        </p>
                                    <? } ?>

                                    <? // Грузополучатель
                                    if (!empty($firstShipment['UF_GRUZOPOLUCHATEL']) && !empty($arResult['GRUZOPOLUCHATEL'][$firstShipment['UF_GRUZOPOLUCHATEL']])) { ?>
                                        <p><?= Loc::getMessage('SPOL_ORDER_GRUZOPOLUCHATEL'); ?>:&nbsp;
                                            <span>
                                                <?= $arResult['GRUZOPOLUCHATEL'][$firstShipment['UF_GRUZOPOLUCHATEL']]['UF_NAME']; ?>
                                            </span>
                                        </p>
                                    <? } ?>

                                    <? // Файлы отгрузки
                                    if (!empty($arResult['OTGRUZKA_FILES'])) {
                                        $thisFiles = [];
                                        foreach ($arResult['OTGRUZKA_FILES'] as $file) {
                                            if ($file['UF_ID'] === $firstShipment['UF_ID']) {
                                                $thisFiles[] = $file;
                                            }
                                        }

                                        if (!empty($thisFiles)) {
                                            ?>
                                          <p><?= Loc::getMessage('SPOL_ORDER_FILES'); ?>:&nbsp;
                                              <? foreach ($thisFiles as $file) {?>
                                                <a href="<?= $file['FILE']['SRC']; ?>" class="lk-order-i__link"
                                                   download>
                                                  <svg class='i-icon'>
                                                    <use xlink:href='#icon-file'/>
                                                  </svg>
                                                  <span>Скачать <?= $file['FILE_NAME']; ?></span>
                                                </a>
                                              <? } ?>
                                          </p>
                                            <?
                                        }
                                    } ?>

                                <? } ?>
                            </div>

                            <div class="lk-orders-i__bottom">
                                <a href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"]) ?>"
                                   title="<?= Loc::getMessage('SPOL_TPL_MORE_ON_ORDER') ?>"
                                   class="link-underline"><?= Loc::getMessage('SPOL_TPL_MORE_ON_ORDER') ?></a>
                                <a href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"]) ?>"
                                   title="<?= Loc::getMessage('SPOL_TPL_REPEAT_ORDER') ?>"
                                   class="link-underline lk-orders-i__repeat">
                                    <svg class='i-icon'>
                                        <use xlink:href='#icon-repeat'/>
                                    </svg>
                                    <?= Loc::getMessage('SPOL_TPL_REPEAT_ORDER') ?>
                                </a>
                                <? if ($order["ORDER"]['CANCELED'] != 'Y'
                                    && $order["ORDER"]['STATUS_ID'] != \Citfact\Sitecore\Order\OrderRepository::STATUS_ID_PAYED
                                    && empty($order['SHIPMENT'])) { ?>
                                    <a href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_CANCEL"]) ?>"
                                       title="<?= Loc::getMessage('SPOL_TPL_CANCEL_ORDER') ?>"
                                       class="link-underline lk-orders-i__cancel"><?= Loc::getMessage('SPOL_TPL_CANCEL_ORDER') ?></a>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                <? } ?>
            <? } else { ?>
                <div class="c__empty" style="display: block;">
                    <h3>Заказы с такими параметрами не найдены.</h3>
                </div>
            <? } ?>
            <!-- /orders !-->
            </div>

            <?

            if($arResult["NAV_RESULT"]->nEndPage > 1 && $arResult["NAV_RESULT"]->NavPageNomer<$arResult["NAV_RESULT"]->nEndPage):?>
                <!-- btn !-->
                <div class="sale__more" id="btn_<?=$bxajaxid?>">
                    <a class="btn btn--loading"
                       data-ajax-id="<?=$bxajaxid?>"
                       href="javascript:void(0)"
                       data-show-more="<?=$arResult["NAV_RESULT"]->NavNum?>"
                       data-next-page="<?=($arResult["NAV_RESULT"]->NavPageNomer + 1)?>"
                       data-max-page="<?=$arResult["NAV_RESULT"]->nEndPage?>">
                        <svg class='i-icon'>
                            <use xlink:href='#icon-loading'/>
                        </svg>
                        <span>Загрузить ещё</span>
                        <span>Загружается</span>
                    </a>
                </div>
                <!-- /btn !-->
            <?endif?>

        </div>
    </div>
<? } ?>
<script>
    BX.message({
        COMPONENT_PATH_SALE_PERSONAL_ORDER: location.href
    });
</script>
