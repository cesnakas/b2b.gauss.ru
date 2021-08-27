<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

CJSCore::Init(array('clipboard', 'fx'));

if($arResult['1C_NUMBER']){
    $title = Loc::getMessage('SPOD_LIST_MY_ORDER', array(
        '#ACCOUNT_NUMBER#' => htmlspecialcharsbx($arResult["1C_NUMBER"]),
        '#DATE_ORDER_CREATE#' => $arResult["DATE_INSERT"]->format('d.m.Y')
    ));
   //$APPLICATION->SetTitle($title);
} else {
    $title = Loc::getMessage('SPOD_LIST_MY_ORDER', array(
        '#ACCOUNT_NUMBER#' => htmlspecialcharsbx($arResult["ID"]),
        '#DATE_ORDER_CREATE#' => $arResult["DATE_INSERT"]->format('d.m.Y')
    ));
   //$APPLICATION->SetTitle($title);
}


$ghostProducts = array_diff_key($arResult['BASKET'], $arResult['CATALOG_ITEMS']);


if($arResult['1C_NUMBER']){
    $APPLICATION->AddChainItem('Заказ №' . $arResult["1C_NUMBER"]);
} else{
    $APPLICATION->AddChainItem('Заказ №' . $arResult["ID"]);

}

if (!empty($arResult['ERRORS']['FATAL'])) {
    foreach ($arResult['ERRORS']['FATAL'] as $error) {
        ShowError($error);
    }

    $component = $this->__component;

    if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED])) {
        $APPLICATION->AuthForm('', false, false, 'N', false);
    }
} else {
    if (!empty($arResult['ERRORS']['NONFATAL'])) {
        foreach ($arResult['ERRORS']['NONFATAL'] as $error) {
            ShowError($error);
        }
    }
    ?>
    <div class="lk-order">
        <div class="lk-order__section lk-order__section--head">
            <span>
                <?= $title; ?>
                <?= $arResult['OFFLINE'] == 'Да' ? '<span class="red">[Offline]</span>' :
                    '<span class="green">[Online]</span>' ?>
            </span>
            <? if (!empty($arResult['SHIPMENT'])) : ?>
                <span><?= Loc::getMessage('SPOD_TPL_GOODS') ?>&nbsp;<?= $arResult['QUANTITY_GOODS_WITH_SHIPMENT'] ?></span>
                <span><?= Loc::getMessage('SPOD_ORDER_PRICE_WITH_SHIPMENT') ?>:&nbsp;<?= $arResult['FORMATED_SUMM_SHIPMENT'] ?></span>
            <? else : ?>
                <span><?= Loc::getMessage('SPOD_TPL_GOODS') ?>&nbsp;<?= count($arResult['BASKET']) ?></span>
                <span><?= Loc::getMessage('SPOD_ORDER_PRICE_WITHOUT_SHIPMENT') ?>:&nbsp;<?= $arResult['PRICE_FORMATED'] ?></span>
            <? endif; ?>
        </div>
        <div class="lk-order__section lk-order__section--bg">
            <div class="lk-order__title">Информация о заказе</div>
            <? if ($arResult['USER']['IS_MANAGER_OR_ASSISTANT']) { ?>
                <div class="lk-order__from">Заказ инициирован менеджером</div>
                <?
            } else { ?>
                <div class="lk-order__from">Заказ инициирован пользователем</div>
            <? } ?>

            <div class="lk-order__links">
                <a href="<?= !empty($ghostProducts) || $ghostProducts === null ? "javascript:Am.modals.showDialog('/local/include/modals/ghost_products.php?order-id=" . $arResult['ID'] . "');" : htmlspecialcharsbx($arResult['URL_TO_COPY']); ?>"
                   title="<?= Loc::getMessage('SPOD_ORDER_REPEAT') ?>"
                   class="link-underline">
                    <svg class='i-icon'>
                        <use xlink:href='#icon-repeat'/>
                    </svg>
                    <?= Loc::getMessage('SPOD_ORDER_REPEAT') ?>
                </a>
                <? if ($arResult['CANCELED'] !== 'Y'
                    && $arResult['STATUS_ID'] != \Citfact\Sitecore\Order\OrderRepository::STATUS_ID_PAYED
                    && empty($arResult['OTGRUZKA_FILES'])) { ?>
                    <a href="<?= htmlspecialcharsbx($arResult['URL_TO_CANCEL']) ?>"
                       title="<?= Loc::getMessage('SPOD_ORDER_CANCEL') ?>"
                       class="link-underline"><?= Loc::getMessage('SPOD_ORDER_CANCEL') ?></a>
                <? } ?>
            </div>
        </div>
        <div class="lk-order__section">

            <div class="lk-order__params">
                <? if ($arResult['USER']['CONTRAGENT']['UF_NAME']) { ?>
                    <div>
                        <span>Юр.лицо:</span>
                        <span><?= $arResult['USER']['CONTRAGENT']['UF_NAME'] ?></span>
                    </div>
                    <?
                } ?>
                <div>
                    <span>Оформил заказ:</span>
                    <span>
                        <?
                        $userName = $arResult["USER_NAME"];
                        if (strlen($userName)) {
                            echo htmlspecialcharsbx($userName);
                        } elseif (strlen($arResult['FIO'])) {
                            echo htmlspecialcharsbx($arResult['FIO']);
                        } else {
                            echo htmlspecialcharsbx($arResult["USER"]['LOGIN']);
                        }
                        ?>
                    </span>
                </div>
                <? if ($arResult['USER']['MANAGER']) { ?>
                    <div>
                        <span>Менеджер клиента:</span>
                        <span><?= $arResult['USER']['MANAGER']['NAME'] . ' ' . $arResult['USER']['MANAGER']['LAST_NAME'] ?></span>
                    </div>
                    <?
                } ?>
                <div>
                    <span>Текущий статус от <?= $arResult['DATE_STATUS']->format('d.m.Y') ?></span>
                    <? if ($arResult['CANCELED'] === 'Y') {
                        echo '<span class="red">' . Loc::getMessage('SPOD_ORDER_CANCELED') . '</span>';
                    } elseif ($arResult['STATUS']['ID'] === 'N') {
                        echo '<span class="yellow">' . $arResult['STATUS']['NAME'] . '</span>';
                    } else {
                        echo '<span class="green">' . $arResult['STATUS']['NAME'] . '</span>';
                    } ?>
                </div>
                <div>
                    <? if (!empty($arResult['SHIPMENT'])) : ?>
                        <span><?= Loc::getMessage('SPOD_ORDER_PRICE_WITH_SHIPMENT') ?></span>
                        <span><?= $arResult['FORMATED_SUMM_SHIPMENT'] ?></span>
                    <? else : ?>
                        <span><?= Loc::getMessage('SPOD_ORDER_PRICE_WITHOUT_SHIPMENT') ?></span>
                        <span><?= $arResult['PRICE_FORMATED'] ?></span>
                    <? endif; ?>
                </div>
            </div>


            <? if (!empty($arResult['ORDER_PROPS'])) { ?>
                <div class="lk-order-i" data-toggle-wrap>

                    <div class="lk-order-i__icon">
                        <svg class='i-icon'>
                            <use xlink:href='#icon-doc'/>
                        </svg>
                    </div>

                    <a href="javascript:void(0);" title="Свойства заказа" class="lk-order-i__text link-toggle" data-toggle-btn>
                        <span>Свойства заказа</span>
                        <span>Скрыть свойства заказа</span>
                        <div class="plus"></div>
                    </a>

                    <div class=" hidden" data-toggle-list>
                        <? foreach ($arResult['ORDER_PROPS'] as $prop) { ?>
                            <div class="lk-order-i__text">
                                <span><?= $prop['NAME'] ?>:</span>&nbsp;<span><?= $prop['VALUE'] ?></span>
                            </div>
                        <? } ?>
                    </div>
                </div>
            <? } ?>
        </div>

    <?if (count($arResult['ORDER_FILES'])) {
    ?>
    <div class="lk-order__section lk-order__section--bg">
        <div class="lk-order__title">Документы по заказу</div>
    </div>
    <div class="b-tabs" data-tab-group >
    <div class="b-tabs-head lk-order__section lk-order__section-tabs" data-tab-header>
        <a href="javascript:void(0);" class="b-tabs-link active" data-tab-btn="pdf">PDF</a>
        <a href="javascript:void(0);" class="b-tabs-link" data-tab-btn="excel">EXCEL</a>
    </div>
    <div class="b-tabs__content" data-tab-content>
    <div class="b-tabs__item lk-download__tab--pdf active" data-tab-body="pdf">
        <div class="lk-order__section">
            <div class="lk-order-i__links">
                <? foreach ($arResult['ORDER_FILES'] as $file) {
                    if (strpos($file['FILE']['SRC'], '.pdf')) {
                        ?>
                        <a href="<?= $file['FILE']['SRC']; ?>" class="lk-order-i__link lk-order-i__link--file" download>
                                <span>
                                    <svg class='i-icon'>
                                        <use xlink:href='#icon-pdf2'/>
                                    </svg>
                                    Скачать</span>
                            <span class="lk-order-i__name"><?= $file['FILE_NAME']; ?>_pdf</span>
                        </a>
                    <? } ?>
                <? } ?>
            </div>
        </div>

    </div>
    <div class="b-tabs__item lk-download__tab--excel" data-tab-body="excel">
        <div class="lk-order__section">
            <div class="lk-order-i__links">
                <? foreach ($arResult['ORDER_FILES'] as $file) {
                    if (!strpos($file['FILE']['SRC'], '.pdf')) {
                        ?>
                        <a href="<?= $file['FILE']['SRC']; ?>" class="lk-order-i__link lk-order-i__link--file" download>
                                            <span>
                                                <svg class='i-icon'>
                                                    <use xlink:href='#icon-excel1'/>
                                                </svg>
                                                Скачать</span>
                            <span class="lk-order-i__name"><?= $file['FILE_NAME']; ?></span>
                        </a>
                    <? } ?>
                <? } ?>
            </div>
        </div>
    </div>
<? } ?>

        <?
        if (count($arResult['SHIPMENT'])) {
            ?>
            <div class="lk-order__section lk-order__section--bg">
                <div class="lk-order__title">Отгрузки и доставки</div>
            </div>
            <div class="lk-order__section">

                <?
                foreach ($arResult['SHIPMENT'] as $shipments) {
                    $firstShipment = $shipments[0];
                    ?>
                    <div class="lk-order-i">

                        <div class="lk-order-i__icon">
                            <svg class='i-icon'>
                                <use xlink:href='#icon-delivery'/>
                            </svg>
                        </div>


                        <? if (strlen($firstShipment["UF_NOMER1S"])) { ?>

                            <div class="lk-order-i__text">

                                <?

                                $shipmentRow = Loc::getMessage('SPOD_SUB_ORDER_SHIPMENT') . " " . Loc::getMessage('SPOD_NUM_SIGN') .

                                    " " . $firstShipment["UF_NOMER1S"];

                                echo $shipmentRow;

                                ?>

                            </div>

                        <? } ?>

                        <? if (strlen($firstShipment["UF_SPOSOBDOSTAVKI"])) { ?>

                            <div class="lk-order-i__text">

                                Способ доставки: <?= htmlspecialcharsbx($firstShipment["UF_SPOSOBDOSTAVKI"]) ?>

                            </div>
                            <?
                        }
                        ?>

                        <? if (strlen($firstShipment["UF_TRANSPORTNAYAKOMP"])) { ?>

                            <div class="lk-order-i__text">

                                <?= Loc::getMessage('SPOD_ORDER_DELIVERY') ?>

                                : <?= htmlspecialcharsbx($firstShipment["UF_TRANSPORTNAYAKOMP"]) ?>

                            </div>
                            <?
                        }
                        ?>

                        <? if (strlen($firstShipment["UF_PLATELSHCHIK"])) { ?>

                            <div class="lk-order-i__text">

                                <?= Loc::getMessage('SPOD_PERSON_NAME') ?>

                                : <?= htmlspecialcharsbx($firstShipment["UF_PLATELSHCHIK"]) ?>

                            </div>

                        <? } ?>

                        <?
                        if (!empty($firstShipment['UF_ADRESDOSTAVKI']) && !empty($arResult['ADDRESSES'][$firstShipment['UF_ADRESDOSTAVKI']])) { ?>
                            <div class="lk-order-i__text">
                                Адрес
                                доставки: <?= $arResult['ADDRESSES'][$firstShipment['UF_ADRESDOSTAVKI']]['UF_NAME']; ?>
                            </div>
                        <? } ?>

                        <? if (strlen($firstShipment["UF_STATUS"])) { ?>

                            <div class="lk-order-i__text">

                                <?= Loc::getMessage('SPOD_ORDER_SHIPMENT_STATUS') ?>:

                                <?= htmlspecialcharsbx($firstShipment['UF_STATUS']) ?>

                            </div>

                        <? } ?>

                        <?
                        if (!empty($firstShipment['UF_GRUZOPOLUCHATEL']) && !empty($arResult['GRUZOPOLUCHATEL'][$firstShipment['UF_GRUZOPOLUCHATEL']])) { ?>
                            <div class="lk-order-i__text">
                                Грузополучатель: <?= $arResult['GRUZOPOLUCHATEL'][$firstShipment['UF_GRUZOPOLUCHATEL']]['UF_NAME']; ?>
                            </div>
                        <? } ?>


                        <? if (!empty($firstShipment['UF_TELEFON'])) { ?>
                            <div class="lk-order-i__text">
                                Телефон: <?= $firstShipment['UF_TELEFON']; ?>
                            </div>
                        <? } ?>

                        <?
                        if (!empty($arResult['OTGRUZKA_FILES'])) {
                            ?>
                            <div class="lk-order-i__links">
                                <?php
                                foreach ($arResult['OTGRUZKA_FILES'] as $file) {
                                    if ($file['UF_ID'] === $firstShipment['UF_ID']) { ?>
                                        <a href="<?= $file['FILE']['SRC']; ?>" class="lk-order-i__link" download>
                                            <svg class='i-icon'>
                                                <use xlink:href='#icon-file'/>
                                            </svg>
                                            <span>Скачать <?= $file['FILE_NAME']; ?></span>
                                        </a>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                            <?
                        } ?>


                        <div data-toggle-wrap>
                            <a href="javascript:void(0);" class="link-toggle" data-toggle-btn>
                                <span>Показать товары</span>
                                <span>Скрыть товары</span>
                                <div class="plus"></div>
                            </a>

                            <div class="hidden" data-toggle-list>

                                <div class="basket-item basket-item--top">
                                    <div class="basket-item__description">Наименование</div>
                                    <div class="basket-item__price">Цена</div>
                                    <div class="basket-item__count">Количество</div>
                                    <div class="basket-item__price">Сумма</div>
                                </div>

                                <?
                                foreach ($shipments as $key => $shipment) {
                                    $basketItem = $arResult['BASKET'][$shipment['UF_TOVARID']];
                                    $catalogItem = $arResult['CATALOG_ITEMS'][$basketItem['PRODUCT_XML_ID']];
                                    ?>
                                    <div class="basket-item">
                                        <div class="basket-item__description">

                                            <?php if (!empty($catalogItem['DETAIL_PAGE_URL'])) { ?>
                                                <a href="<?= htmlspecialcharsbx($catalogItem['DETAIL_PAGE_URL']); ?>">
                                                    <span class="basket-item__title">
                                                        <?= htmlspecialcharsbx($basketItem['NAME']) ?>
                                                    </span>
                                                </a>
                                            <?php } else {?>
                                                <div>
                                                    <span class="basket-item__title">
                                                        <?= htmlspecialcharsbx($basketItem['NAME']) ?>
                                                    </span>
                                                </div>
                                            <?php } ?>

                                            <div class="basket-item__article">
                                                Артикул:&nbsp;&nbsp;<?= $catalogItem['PROPERTY_CML2_ARTICLE_VALUE'] ?: 'отсутствует'; ?>
                                            </div>
                                        </div>

                                        <div class="basket-item__price">
                                            <div class="basket-item__t">Цена</div>
                                            <span><?= $shipment['UF_TSENA'] . ' ₽' ?></span>
                                        </div>

                                        <div class="basket-item__count">
                                            <div class="basket-item__t">Количество</div>
                                            <span><?= $shipment['UF_KOLICHESTVO'] ?></span>
                                        </div>

                                        <div class="basket-item__price">
                                            <div class="basket-item__t">Итого</div>
                                            <span><?= $shipment['UF_SUMMA'] . ' ₽' ?></span>
                                        </div>
                                    </div>
                                <? } ?>
                            </div>
                        </div>

                    </div>
                <? } ?>
            </div>
            <?
        }

        if (count($arResult['BASKET'])) {
            ?>

            <div class="lk-order__section lk-order__section--bg">
                <div class="lk-order__title"><?= Loc::getMessage('SPOD_ORDER_BASKET') ?></div>
            </div>
            <div class="lk-order__section">
                <div class="basket-item basket-item--top">
                    <div class="basket-item__description"><?= Loc::getMessage('SPOD_NAME') ?></div>
                    <div class="basket-item__price"><?= Loc::getMessage('SPOD_PRICE') ?></div>
                    <div class="basket-item__count"><?= Loc::getMessage('SPOD_QUANTITY') ?></div>
                    <div class="basket-item__price"><?= Loc::getMessage('SPOD_ORDER_PRICE') ?></div>
                    <div class="basket-item__delivery"><?= Loc::getMessage('SPOD_ORDER_STATUS') ?></div>
                </div>

                <?
                foreach ($arResult['BASKET_STATUS'] as $key => $basketStatus) {
                    foreach ($basketStatus as $basketItem) {
                        $catalogItem = $arResult['CATALOG_ITEMS'][$basketItem['UF_NOMENKLATURAID']];
                        ?>
                        <div class="basket-item">
                            <div class="basket-item__description">

                                <?php if (!empty($catalogItem['DETAIL_PAGE_URL'])) { ?>
                                    <a href="<?= htmlspecialcharsbx($catalogItem['DETAIL_PAGE_URL']); ?>">
                                        <span class="basket-item__title">
                                            <?= htmlspecialcharsbx($arResult['BASKET'][$key]['NAME']) ?>
                                        </span>
                                    </a>
                                <?php } else {?>
                                    <div>
                                        <span class="basket-item__title">
                                            <?= htmlspecialcharsbx($arResult['BASKET'][$key]['NAME']) ?>
                                        </span>
                                    </div>
                                <?php } ?>

                                <div class="basket-item__article">
                                    Артикул:&nbsp;&nbsp;<?= $catalogItem['PROPERTY_CML2_ARTICLE_VALUE'] ?: 'отсутствует'; ?>
                                </div>
                            </div>

                            <div class="basket-item__price">
                                <div class="basket-item__t"><?= Loc::getMessage('SPOD_PRICE') ?></div>
                                <span><?= $arResult['BASKET'][$key]['PRICE_FORMATED'] ?></span>
                            </div>

                            <div class="basket-item__count">
                                <div class="basket-item__t"><?= Loc::getMessage('SPOD_QUANTITY') ?></div>
                                <span><?= $basketItem['UF_KOLICHESTVO'] ?></span>
                            </div>

                            <div class="basket-item__price">
                                <div class="basket-item__t"><?= Loc::getMessage('SPOD_ORDER_PRICE') ?></div>
                                <span><?= $basketItem['FORMATED_SUM'] ?></span>
                            </div>

                            <div class="basket-item__delivery">
                                <div class="basket-item__t">Статус</div>
                                <span class="<?= $basketItem['UF_STATUS_CLASS'] ?>">
                                    <?= $basketItem['UF_STATUS'] ?>
                                    <?= ($basketItem['UF_SROKPOSTAVKI']) ? $basketItem['UF_SROKPOSTAVKI'] : ''; ?>
                                </span>
                            </div>
                        </div>
                    <? }
                } ?>

                <div class="lk-order__bottom">
                    <a href="<?= $arParams['PATH_TO_LIST']; ?>" class="link-more link-more--back">
                        <svg class='i-icon'>
                            <use xlink:href='#icon-arrow-r'/>
                        </svg>
                        <span>Вернуться в список заказов</span>
                    </a>
                    <div class="title-2">Итого:
                        <? if (!empty($arResult['SHIPMENT'])) :
                            echo $arResult['FORMATED_SUMM_SHIPMENT'];
                        else :
                            echo \SaleFormatCurrency(
                                $arResult['PRODUCT_SUM'],
                                $arResult['CURRENCY']
                            );
                        endif; ?>
                    </div>
                </div>
            </div>
            <?
        } ?>
    </div>
<? } ?>