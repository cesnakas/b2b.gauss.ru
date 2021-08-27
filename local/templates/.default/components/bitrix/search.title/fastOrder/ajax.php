<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

if(!empty($arResult["CATEGORIES"])): ?>
        <div class="title-search_main">
            <div>
                <?php
                foreach(reset($arResult["CATEGORIES"])["ITEMS"] as $i => $arItem):
                    if($arItem['TYPE'] !== 'all'):
                        $vendorCode = array_pop(explode(' ', $arItem["NAME"]));
                        ?>
                        <div class="title-search"
                             style="display: block"
                             data-input-id="<?php echo $arParams['INPUT_ID']; ?>"
                             data-vendor-code="<?php echo $vendorCode; ?>">
                            <div class="title-search-item">
                                <a href="javascript:void(0);">
                                    <?php echo $arItem["NAME"]; ?>
                                </a>
                            </div>
                        </div>
                    <?php endif;
                endforeach;?>
            </div>
        </div>
    <?php
endif; ?>

<script>
    Am.order.initFastOrderForm();
</script>
