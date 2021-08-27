<? use Bitrix\Main\Localization\Loc;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>
<?
$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

if($arParams["SHOW_INPUT"] !== "N"):?>
    <div class="b-modal b-modal--fo">
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

                <?php for ($i = 0; $i < 5; $i++) { ?>
                    <div class="b-modal-f__item">


                        <div class="b-form__item"
                             id="<?php echo $CONTAINER_ID . '-' . $i; ?>">
                            <input type="text"
                                   class="b-modal-f__input"
                                   id="<?php echo $INPUT_ID . '-' . $i; ?>"
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

                    <script>
                      BX.ready(function(){
                        new JCTitleSearch({
                          'AJAX_PAGE' : '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
                          'CONTAINER_ID': '<?php echo $CONTAINER_ID . '-' . $i; ?>',
                          'INPUT_ID': '<?php echo $INPUT_ID . '-' . $i; ?>',
                          'MIN_QUERY_LEN': 2
                        });
                      });
                    </script>
                <?php } ?>

                <div data-toggle-wrap>
                    <a href="javascript:void(0);" class="link-toggle" data-toggle-btn>
                        <span>Показать больше полей</span>
                        <span>Скрыть дополнительные поля</span>
                        <div class="plus"></div>
                    </a>

                    <div class="hidden" data-toggle-list>
                        <?php for ($i = 0; $i < 5; $i++) { ?>
                            <div class="b-modal-f__item">
                                <div class="b-form__item"
                                     id="<?php echo $CONTAINER_ID . '-extra-' . $i; ?>">
                                    <input type="text"
                                           class="b-modal-f__input"
                                           id="<?php echo $INPUT_ID . '-extra-' . $i; ?>"
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

                            <script>
                              BX.ready(function(){
                                new JCTitleSearch({
                                  'AJAX_PAGE' : '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
                                  'CONTAINER_ID': '<?php echo $CONTAINER_ID . '-extra-' . $i; ?>',
                                  'INPUT_ID': '<?php echo $INPUT_ID . '-extra-' . $i; ?>',
                                  'MIN_QUERY_LEN': 2
                                });
                              });
                            </script>
                        <?php } ?>
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
<?endif?>
