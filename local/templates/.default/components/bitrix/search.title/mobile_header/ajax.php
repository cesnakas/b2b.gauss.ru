<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
if(!empty($arResult["CATEGORIES"])):
    foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
        <div class="title-search_main">
            <div class="title-search_main-title">
                <div class="title-search_main-category">
                    <?php echo $arCategory['TITLE']; ?>
                </div>
            </div>

            <div>
                <?php
                foreach($arCategory["ITEMS"] as $i => $arItem):
                    if($category_id === "all"):?>
                        <div class="title-search">
                            <div class="title-search-all">
                                <a href="<?php echo $arItem["URL"]; ?>" class="link-more">
                                    <span><?php echo $arItem["NAME"]; ?></span>
                                    <svg class="i-icon">
                                        <use xlink:href="#icon-arrow-r" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php elseif($arItem['TYPE'] !== 'all'): ?>
                        <div class="title-search" style="display: block">
                            <div class="title-search-item">
                                <a href="<?php echo $arItem["URL"]; ?>">
                                    <?php echo $arItem["NAME"]; ?>
                                </a>
                            </div>
                        </div>
                    <?php endif;
                endforeach;?>
            </div>
        </div>
    <?php endforeach;
endif;