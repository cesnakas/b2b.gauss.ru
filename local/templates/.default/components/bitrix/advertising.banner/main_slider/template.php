<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Citfact\Sitecore\Core;

$core = Core::getInstance();
?>
<div class="main-slider slider">
    <div class="swiper-container" <?php if (count($arResult['BANNERS']) > 1) {?>data-slider="main"<?php } ?>>
        <div class="swiper-wrapper">
            <?php
            $count = 0;
            foreach ($arResult['BANNERS'] as $arGroup) { ?>
                <div class="swiper-slide main-slider__slide main-slider__full-img<?= $count == 0 ? ' active' : '' ?>
                <? /* main-slider__full-img класс для отображения изображения на весь баннер*/ ?>
                <? /* main-slider__no-inner класс для скрытия текста и кнопки */?>
                <? /* main-slider__three класс для отображения трех баннеров*/?>
                ">
                    <div class="main-slider__big main-slider__container-three">
                        <a class="main-slider__item">
                            <div class="main-slider__inner">
                                <span><?= $arGroup['1']['NAME'] ?></span>
                                <div class="title-1">
                                    <span>
                                        <?= $arGroup['1']['COMMENTS'] ?>
                                    </span>
                                </div>
                                <div class="btn btn--transparent btn--big">Подробнее</div>
                            </div>
                            <div class="main-slider__img">
                                <img src="<?= $arGroup['1']['IMAGE']['SRC']['ORIGIN'] ?>" alt="">
                            </div>
                        </a>
                        <a class="main-slider__item">
                            <div class="main-slider__inner">
                                <span><?= $arGroup['1']['NAME'] ?></span>
                                <div class="title-1">
                                    <span>
                                        <?= $arGroup['1']['COMMENTS'] ?>
                                    </span>
                                </div>
                                <div class="btn btn--transparent btn--big">Подробнее</div>
                            </div>
                            <div class="main-slider__img">
                                <img src="<?= $arGroup['1']['IMAGE']['SRC']['ORIGIN'] ?>" alt="">
                            </div>
                        </a>
                        <a class="main-slider__item">
                            <div class="main-slider__inner">
                                <span><?= $arGroup['1']['NAME'] ?></span>
                                <div class="title-1">
                                    <span>
                                        <?= $arGroup['1']['COMMENTS'] ?>
                                    </span>
                                </div>
                                <div class="btn btn--transparent btn--big">Подробнее</div>
                            </div>
                            <div class="main-slider__img">
                                <img src="<?= $arGroup['1']['IMAGE']['SRC']['ORIGIN'] ?>" alt="">
                            </div>
                        </a>
                    </div>
                    <?php if (!empty($arGroup['1'])) { ?>
                        <a href="<?= $arGroup['1']['URL']; ?>" class="main-slider__big main-slider__no-three <?php echo count($arGroup) === 1 ? 'one' : ''; ?>" rel="nofollow">
                            <div class="main-slider__inner">
                                <span><?= $arGroup['1']['NAME'] ?></span>
                                <div class="title-1">
                                    <span>
                                        <?= $arGroup['1']['COMMENTS'] ?>
                                    </span>
                                </div>
                                <div class="btn btn--transparent btn--big">Подробнее</div>
                            </div>
                            <div
                                class="main-slider__img <?= $arGroup['1']['IMAGE']['EXT']==='png' ? 'main-slider__img--transparent' : '' ?>">
                                <? if ($count == 0) { ?>
                                    <img class="lazy lazy--replace"
                                         src="<?= $arGroup['1']['IMAGE']['SRC']['ORIGIN'] ?>"
                                         data-src="<?= $arGroup['1']['IMAGE']['SRC']['ORIGIN'] ?>"
                                         data-src-m="<?= $arGroup['1']['IMAGE']['SRC']['MOBILE'] ?>"
                                         title="<?= $arGroup['1']['NAME'] ?>"
                                         alt="<?= $arGroup['1']['NAME'] ?>">
                                <? } else { ?>
                                    <img class="lazy"
                                         src="<?= \Citfact\SiteCore\Core::IMAGE_PLACEHOLDER_TRANSPARENT; ?>"
                                         data-src="<?= $arGroup['1']['IMAGE']['SRC']['ORIGIN'] ?>"
                                         title="<?= $arGroup['1']['NAME'] ?>"
                                         alt="<?= $arGroup['1']['NAME'] ?>">
                                <? } ?>
                            </div>
                        </a>
                    <?php } ?>

                    <?php if (!empty($arGroup['2'])) { ?>
                        <a href="<?= $arGroup['2']['URL']; ?>" class="main-slider__small <?php echo count($arGroup) === 1 ? 'one' : ''; ?>">
                            <div
                                class="main-slider__img <?= $arGroup['2']['IMAGE']['EXT']==='png' ? 'main-slider__img--transparent' : '' ?>">
                                <? if ($count == 0) { ?>
                                    <img class="lazy lazy--replace"
                                         src="<?= $arGroup['2']['IMAGE']['SRC']['ORIGIN'] ?>"
                                         data-src="<?= $arGroup['2']['IMAGE']['SRC']['ORIGIN'] ?>"
                                         data-src-m="<?= $arGroup['2']['IMAGE']['SRC']['MOBILE'] ?>"
                                         title="<?= $arGroup['2']['NAME'] ?>"
                                         alt="<?= $arGroup['2']['NAME'] ?>">
                                <? } else { ?>
                                    <img class="lazy <?= $count == 0 ? " lazy--replace" : "" ?>"
                                         src="<?= \Citfact\SiteCore\Core::IMAGE_PLACEHOLDER_TRANSPARENT; ?>"
                                         data-src="<?= $arGroup['2']['IMAGE']['SRC']['ORIGIN'] ?>"
                                         title="<?= $arGroup['2']['NAME'] ?>"
                                         alt="<?= $arGroup['2']['NAME'] ?>">
                                <? } ?>
                            </div>
                            <div class="title-2"><?= $arGroup['2']['NAME'] ?></div>
                            <span><?= $arGroup['2']['COMMENTS'] ?></span>
                        </a>
                    <?php } ?>
                </div>
                <? $count++;
            }; ?>
        </div>
    </div>
    <?php if (count($arResult['BANNERS']) > 1) {?>
        <div class='slider__paginations' data-slider-p='main'></div>
        <div class='slider__arrows'>
            <div class='slider__arrow slider__arrow--prev' data-slider-arrow-p="main">
                <svg class='i-icon'>
                    <use xlink:href='#icon-arrow-r'/>
                </svg>
            </div>
            <div class='slider__arrow slider__arrow--next' data-slider-arrow-n="main">
                <svg class='i-icon'>
                    <use xlink:href='#icon-arrow-r'/>
                </svg>
            </div>
        </div>
    <?php } ?>
</div>