<?php
define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//ajax
/*if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    \Bitrix\Iblock\Component\Tools::process404(
        '404 Not Found'
        ,true
        ,"Y"
        ,"Y"
        , ""
    );
}*/
?>
<?php
$APPLICATION->IncludeComponent(
    "bitrix:search.title",
    "fastOrder",
    array(
        "CATEGORY_0" => array(
            0 => "no",
        ),
        "CATEGORY_0_TITLE" => "",
        "CATEGORY_0_iblock_1c_catalog" => array(
            0 => "10",
        ),
        "CATEGORY_0_iblock_catalog" => array(
            0 => "1",
        ),
        "CATEGORY_OTHERS_TITLE" => "",
        "CHECK_DATES" => "N",
        "NUM_CATEGORIES" => "1",
        "ORDER" => "date",
        "PAGE" => "#SITE_DIR#search/index.php",
        "SHOW_OTHERS" => "N",
        "TOP_COUNT" => "5",
        "USE_LANGUAGE_GUESS" => "Y",
        "COMPONENT_TEMPLATE" => "fastOrder",
        "SHOW_INPUT" => "Y",
        "INPUT_ID" => $_REQUEST['INPUT_ID'] ?: "title-search-input-vendor-code",
        "CONTAINER_ID" => "title-search-vendor-code"
    ),
    false
);
?>

<?php /* ?>
<div class="b-modal">
    <div class="b-modal__close" data-modal-close="">
        <div class="plus plus--cross"></div>
    </div>

    <div class="title-1">
        <span>Быстрый заказ</span>
    </div>

    <div class="b-modal-f">
        <form method="post" id="fast_order_some_form" action="/" name="order__fastsome_form"
              class="b-form"
              enctype="multipart/form-data">
            
            <? for ($i = 0; $i < 5; $i++) { ?>
                <div class="b-modal-f__item">
                    <div class="b-form__item">
                        <input type="text"
                               class="b-modal-f__input"
                               name="products[productCode][]"
                               data-input-mask="number10">
                    </div>

                    <div class="b-count" data-input-count>
                        <button type="button" data-input-count-btn="minus" class="b-count__btn b-count__btn--minus"></button>
                        <input class="b-count__input"
                               type="text"
                               name="products[quantity][]" min="1" pattern="[0-9]+"
                               value="1"
                               data-input-count-input data-product-quantity="1">
                        <button type="button" data-input-count-btn="plus" class="b-count__btn b-count__btn--plus"></button>
                    </div>
                </div>
            <? } ?>

            <div data-toggle-wrap>
                <a href="javascript:void(0);" class="link-toggle" data-toggle-btn>
                    <span>Показать больше полей</span>
                    <span>Скрыть дополнительные поля</span>
                    <div class="plus"></div>
                </a>

                <div class="hidden" data-toggle-list>
                    <? for ($i = 0; $i < 5; $i++) { ?>
                        <div class="b-modal-f__item">
                            <div class="b-form__item">
                                <input type="text"
                                       class="b-modal-f__input"
                                       name="products[productCode][]"
                                       data-input-mask="number10">
                            </div>

                            <div class="b-count" data-input-count>
                                <button type="button" data-input-count-btn="minus" class="b-count__btn b-count__btn--minus"></button>
                                <input class="b-count__input"
                                       type="text"
                                       name="products[quantity][]" min="1" pattern="[0-9]+"
                                       value="1"
                                       data-input-count-input data-product-quantity="1">
                                <button type="button" data-input-count-btn="plus" class="b-count__btn b-count__btn--plus"></button>
                            </div>
                        </div>
                    <? } ?>
                </div>
            </div>

            <div class="b-modal-f__bottom">
                <a href="/personal/load_order/"
                   class="btn btn--transparent">Быстрый заказ с импортом из Excel</a>
                <a href="#"
                   class="btn btn--transparent"
                   id="send_fast_order_some">К заказу</a>
            </div>

        </form>
    </div>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php"); */ ?>