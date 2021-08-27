<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Citfact\Sitecore\Core;

$core = Core::getInstance(); ?>
<div class="main-slider slider">
    <div class="main-slider__wrapper">
        <div class="main-slider__big">
            <div class="swiper-container" data-slider="main">
                <div class="swiper-wrapper">
                    <? $count = 0;
                    foreach ($arResult['SIMPLE'] as $s) { ?>
                        <? if ($s['TYPE'] == 'THREE') { ?>
                            <? foreach ($s['BANNERS'] as $b) { ?>
                                <div class="swiper-slide main-slider__slide main-slider__container-three <?= $count == 0 ? ' active' : '' ?>">
                                    <? if (!empty($b['GIF'])): ?>
                                        <div href="<?= $s['PROPERTY_SRC_BAN_VALUE']; ?>" class="main-slider__item">
                                            <div class="main-slider__img">
                                                <img src="<?= $b['GIF'] ?>" alt="">
                                            </div>
                                            <div class="main-slider__inner" data-test="test">
                                                <? if ($b['PROPERTY_HIDE_EL_VALUE_XML_ID'] == 'NOH') { ?>
                                                    <span><?= $b['NAME'] ?></span>
                                                <? } ?>
                                                <div class="title-1">
                                                    <span><?= $b["PROPERTY_TEXT_VALUE"] ?></span>
                                                </div>
                                                <? if ($b['PROPERTY_HIDE_EL_VALUE_XML_ID'] == 'NOH') { ?>
                                                    <div class="main-slider__btns">
                                                        <? if ($b['PROPERTY_SRC_BAN_VALUE']) { ?>
                                                            <a href="<?= $b['PROPERTY_SRC_BAN_VALUE'] ?>"
                                                               class="btn btn--transparent btn--big">Каталог товаров</a>
                                                        <? } ?>
                                                        <? if ($b['PROPERTY_SRC_BAN_2_VALUE']) { ?>
                                                            <a href="<?= $b['PROPERTY_SRC_BAN_2_VALUE'] ?>"
                                                               class="link-more">
                                                                <span>Новость</span>
                                                                <svg class='i-icon'>
                                                                    <use xlink:href='#icon-arrow-r'/>
                                                                </svg>
                                                            </a>
                                                        <? } ?>
                                                    </div>
                                                <? } else { ?>
                                                    <div>
                                                        <br/><br/>
                                                    </div>
                                                <? } ?>
                                            </div>
                                        </div>
                                    <? elseif (!empty($b['VIDEO'])): ?>
                                    <div class="main-slider__item">
                                        <video class="main-slider__video lazy" autoplay loop>
                                            <source src="<?= $b['VIDEO'] ?>" type="video/mp4">
                                        </video>
                                        <div class="main-slider__inner" data-test="test">
                                            <? if ($b['PROPERTY_HIDE_EL_VALUE_XML_ID'] == 'NOH') { ?>
                                                <span><?= $b['NAME'] ?></span>
                                            <? } ?>
                                            <div class="title-1">
                                                <span><?= $b["PROPERTY_TEXT_VALUE"] ?></span>
                                            </div>
                                            <? if ($b['PROPERTY_HIDE_EL_VALUE_XML_ID'] == 'NOH') { ?>
                                                <div class="main-slider__btns">
                                                    <? if ($b['PROPERTY_SRC_BAN_VALUE']) { ?>
                                                        <a href="<?= $b['PROPERTY_SRC_BAN_VALUE'] ?>"
                                                           class="btn btn--transparent btn--big">Каталог товаров</a>
                                                    <? } ?>
                                                    <? if ($b['PROPERTY_SRC_BAN_2_VALUE']) { ?>
                                                        <a href="<?= $b['PROPERTY_SRC_BAN_2_VALUE'] ?>"
                                                           class="link-more">
                                                            <span>Новость</span>
                                                            <svg class='i-icon'>
                                                                <use xlink:href='#icon-arrow-r'/>
                                                            </svg>
                                                        </a>
                                                    <? } ?>
                                                </div>
                                            <? } else { ?>
                                                <div>
                                                    <br/><br/>
                                                </div>
                                            <? } ?>
                                        </div>
                                    </div>
                                    <? else: ?>
                                        <div class="main-slider__item">
                                            <div class="main-slider__img">
                                                <img
                                                    <? if ($count) { ?>
                                                        src="<?= $core::IMAGE_PLACEHOLDER_TRANSPARENT ?>"
                                                        data-src="<?= $b['IMAGE']['SRC']['ORIGIN'] ?>"
                                                        class="lazy"
                                                    <? } else { ?>
                                                        src="<?= $b['IMAGE']['SRC']['ORIGIN'] ?>"
                                                    <? } ?>
                                                        alt="">
                                            </div>
                                            <div class="main-slider__inner">
                                                <? if ($b['PROPERTY_HIDE_EL_VALUE_XML_ID'] == 'NOH'): ?>
                                                    <span><?= $b['NAME'] ?></span>
                                                <? else: ?>
                                                    <div>
                                                        <br/><br/>
                                                    </div>
                                                <? endif; ?>
                                                <div class="title-1">
                                                <span>
                                                    <?= $b["PROPERTY_TEXT_VALUE"] ?>
                                                </span>
                                                </div>
                                                <? if ($b['PROPERTY_HIDE_EL_VALUE_XML_ID'] == 'NOH') { ?>
                                                    <div class="main-slider__btns">
                                                        <? if ($b['PROPERTY_SRC_BAN_VALUE']) { ?>
                                                            <a href="<?= $b['PROPERTY_SRC_BAN_VALUE'] ?>"
                                                               class="btn btn--transparent btn--big">Каталог товаров</a>
                                                        <? } ?>
                                                        <? if ($b['PROPERTY_SRC_BAN_2_VALUE']) { ?>
                                                            <a href="<?= $b['PROPERTY_SRC_BAN_2_VALUE'] ?>"
                                                               class="link-more">
                                                                <span>Статья</span>
                                                                <svg class='i-icon'>
                                                                    <use xlink:href='#icon-arrow-r'/>
                                                                </svg>
                                                            </a>
                                                        <? } ?>
                                                    </div>
                                                <? } ?>
                                            </div>
                                        </div>
                                    <? endif; ?>
                                </div>
                            <? } ?>
                        <? } else { ?>
                            <div class="swiper-slide main-slider__slide main-slider__no-three <? if ($s['PROPERTY_FORMAT_VALUE_XML_ID'] == 'ALL_SPACE') { ?> main-slider__full-img<? } ?> <? echo count($arGroup) === 1 ? 'one' : ''; ?>">
                                <? if (!empty($s['VIDEO'])): ?>
                                    <video class="main-slider__item main-slider__video" autoplay loop>
                                        <source src="<?= $s['VIDEO'] ?>" type="video/mp4">
                                    </video>
                                <? else: ?>
                                    <a href="<?= $s['PROPERTY_SRC_BAN_VALUE']; ?>"
                                       class=""
                                       rel="nofollow">
                                        <div
                                                class="main-slider__img <?= $s['IMAGE']['SRC']['ORIGIN'] === 'png' ? 'main-slider__img--transparent' : '' ?><? /*= $s['IMAGE']['SRC']['ORIGIN']==='gif' ? 'main-slider__img--transparent' : '' */ ?>">
                                            <? if ($count == 0) { ?>
                                                <? if (!empty($s['GIF'])): ?>
                                                    <img class="lazy lazy--replace"
                                                         src="<?= $s['GIF'] ?>"
                                                         data-src="<?= $s['GIF'] ?>"
                                                         data-src-m="<?= $s['GIF'] ?>"
                                                         title="<?= $s['GIF'] ?>"
                                                         alt="<?= $s['GIF'] ?>">
                                                <? else: ?>
                                                    <img class="lazy lazy--replace"
                                                         src="<?= $s['IMAGE']['SRC']['ORIGIN'] ?>"
                                                         data-src="<?= $s['IMAGE']['SRC']['ORIGIN'] ?>"
                                                         data-src-m="<?= $s['IMAGE']['SRC']['MOBILE'] ?>"
                                                         title="<?= $s['NAME'] ?>"
                                                         alt="<?= $s['NAME'] ?>">
                                                <? endif; ?>
                                            <? } else { ?>
                                                <? if (!empty($s['GIF'])): ?>
                                                    <img class="lazy"
                                                         src="<?= \Citfact\SiteCore\Core::IMAGE_PLACEHOLDER_TRANSPARENT; ?>"
                                                         data-src="<?= $s['GIF'] ?>"
                                                         title="<?= $s['NAME'] ?>"
                                                         alt="<?= $s['NAME'] ?>">
                                                <? else: ?>
                                                    <img class="lazy"
                                                         src="<?= \Citfact\SiteCore\Core::IMAGE_PLACEHOLDER_TRANSPARENT; ?>"
                                                         data-src="<?= $s['IMAGE']['SRC']['ORIGIN'] ?>"
                                                         title="<?= $s['NAME'] ?>"
                                                         alt="<?= $s['NAME'] ?>">
                                                <? endif; ?>

                                            <? } ?>
                                        </div>
                                        <div class="main-slider__inner">
                                            <? if ($s['PROPERTY_HIDE_EL_VALUE_XML_ID'] == 'NOH') { ?>
                                                <span><?= $s['NAME'] ?></span>
                                            <? } ?>
                                            <div class="title-1">
                                                <span><?= $s["PROPERTY_TEXT_VALUE"] ?></span>
                                            </div>
                                            <? if ($s['PROPERTY_HIDE_EL_VALUE_XML_ID'] == 'NOH') { ?>
                                                <div class="btn btn--transparent btn--big">Подробнее</div>
                                            <? } ?>
                                        </div>
                                    </a>
                                <? endif; ?>
                            </div>
                        <? } ?>
                        <?
                        $count++;
                    } ?>
                </div>
            </div>
            <? if (count($arResult['SIMPLE']) > 1) { ?>
                <div class="swiper-pagination swiper-pagination--big"></div>
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
            <? } ?>
        </div>
        <div class="main-slider__small">
            <div class="swiper-container" data-slider="main-right">
                <div class="swiper-wrapper">
                    <? $count = 0;
                    foreach ($arResult['RIGHT'] as $r) { ?>
                        <div class="swiper-slide main-slider__slide <?= $count == 0 ? ' active' : '' ?>">
                            <? if (!empty($r['~PROPERTY_VIDEO_SRC_VALUE'])): ?>
                                <div class="main-slider__small main-slider__small-video main-slider__img lazy lazy--replace">
                                    <?= $r['~PROPERTY_VIDEO_SRC_VALUE'] ?>
                                </div>
                            <? elseif (!empty($r['GIF'])): ?>
                                <a href="<?= $r['GIF']; ?>" class="main-slider__small ">
                                    <div class="main-slider__img <?= $r['GIF'] === 'gif' ? 'main-slider__img--transparent' : '' ?>">
                                        <? if ($count == 0) { ?>
                                            <img class="lazy lazy--replace"
                                                 src="<?= $r['GIF'] ?>"
                                                 data-src="<?= $r['GIF'] ?>"
                                                 data-src-m="<?= $r['GIF'] ?>"
                                                 title="<?= $r['NAME'] ?>"
                                                 alt="<?= $r['NAME'] ?>">
                                        <? } else { ?>
                                            <img class="lazy"
                                                 src="<?= \Citfact\SiteCore\Core::IMAGE_PLACEHOLDER_TRANSPARENT; ?>"
                                                 data-src="<?= $r['GIF'] ?>"
                                                 title="<?= $r['NAME'] ?>"
                                                 alt="<?= $r['NAME'] ?>">
                                        <? } ?>
                                    </div>
                                    <div class="main-slider__text">
                                        <? if ($r['PROPERTY_HIDE_EL_VALUE_XML_ID'] == 'NOH'): ?>
                                            <div class="title-2"><?= $r['NAME'] ?></div>
                                        <? else: ?>
                                            <div>
                                                <br/><br/>
                                            </div>
                                        <? endif; ?>
                                        <span class="desc"><?= $r["PROPERTY_TEXT_VALUE"] ?></span>
                                    </div>
                                </a>
                            <? elseif (!empty($r['VIDEO'])): ?>

                                <video class="main-slider__small main-slider__small-video main-slider__img lazy lazy--replace" autoplay loop>
                                    <source src="<?= $r['VIDEO'] ?>" type="video/mp4">
                                </video>

                            <? else: ?>
                                <a href="<?= $r["PROPERTY_SRC_BAN_VALUE"]; ?>" class="main-slider__small ">
                                    <div class="main-slider__img <?= $r['IMAGE']['SRC']['ORIGIN'] === 'png' ? 'main-slider__img--transparent' : '' ?>">
                                        <? if ($count == 0) { ?>
                                            <img class="lazy lazy--replace"
                                                 src="<?= $r['IMAGE']['SRC']['ORIGIN'] ?>"
                                                 data-src="<?= $r['IMAGE']['SRC']['ORIGIN'] ?>"
                                                 data-src-m="<?= $r['IMAGE']['SRC']['MOBILE'] ?>"
                                                 title="<?= $r['NAME'] ?>"
                                                 alt="<?= $r['NAME'] ?>">
                                        <? } else { ?>
                                            <img class="lazy"
                                                 src="<?= \Citfact\SiteCore\Core::IMAGE_PLACEHOLDER_TRANSPARENT; ?>"
                                                 data-src="<?= $r['IMAGE']['SRC']['ORIGIN'] ?>"
                                                 title="<?= $r['NAME'] ?>"
                                                 alt="<?= $r['NAME'] ?>">
                                        <? } ?>
                                    </div>
                                    <div class="main-slider__text">
                                        <div class="title-2"><?= $r['NAME'] ?></div>
                                        <span class="desc"><?= $r["PROPERTY_TEXT_VALUE"] ?></span>
                                    </div>
                                </a>
                            <? endif; ?>
                        </div>
                        <?
                        $count++;
                    } ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
</div>
