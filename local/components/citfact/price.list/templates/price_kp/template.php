<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arResult */
/** @var array $arParams */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);
global $APPLICATION;
$dir = $APPLICATION->GetCurDir();
?>

<?php if ($arResult['SECTIONS']): ?>
<form method="post" action="/personal/price/order/">

    <?php
    foreach ($arResult['SECTIONS'] as $sectionId => $section): ?>

        <div class="s-toggle" data-toggle-wrap>
            <div class="s-toggle__title s-toggle-arrow" data-toggle-btn>
                <span><?= $section['NAME']; ?></span>
            </div>

            <?php if (!empty($section['SECTIONS'])) { ?>
            <div class="s-toggle__list" data-toggle-list>
                <?php
                foreach ($section['SECTIONS'] as $subSectionId => $subSection): ?>
                    <div class="s-toggle s-toggle--in" data-toggle-wrap>
                        <?php
                        if (!empty($subSection['SECTIONS'])) { ?>
                            <div class="s-toggle__title s-toggle-arrow" data-toggle-btn>
                                <span><?= $subSection['NAME']; ?></span>
                            </div>

                            <div class="s-toggle__list" data-toggle-list>
                                <?php
                                foreach ($subSection['SECTIONS'] as $finalSectionId => $finalSection) { ?>
                                    <div class="lk-p-list s-toggle s-toggle--in">
                                        <div class="lk-p-list__item" data-price-item>
                                            <div class="b-checkbox">
                                                <label class="b-checkbox__label">
                                                    <input type="checkbox"
                                                           id="<?php echo $finalSectionId; ?>"
                                                           name="<?php echo $finalSectionId; ?>"
                                                           class="b-checkbox__input">
                                                    <span class="b-checkbox__box">
                                                        <span class="b-checkbox__line b-checkbox__line--short"></span>
                                                        <span class="b-checkbox__line b-checkbox__line--long"></span>
                                                    </span>
                                                    <span class="b-checkbox__text">
                                                        <?= $finalSection['NAME']; ?>
                                                    </span>
                                                </label>
                                            </div>
                                            <a href="javascript:void(0);" data-price-eye class="lk-p-list__look">
                                                <svg class="i-icon">
                                                    <use xlink:href="#icon-watch"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                        <?php } else { ?>

                            <div class="lk-p-list s-toggle s-toggle--in">
                                <div class="lk-p-list__item" data-price-item>
                                    <div class="b-checkbox">
                                        <label class="b-checkbox__label">
                                            <input type="checkbox"
                                                   id="<?php echo $subSectionId; ?>"
                                                   name="<?php echo $subSectionId; ?>"
                                                   class="b-checkbox__input">
                                            <span class="b-checkbox__box">
                                                <span class="b-checkbox__line b-checkbox__line--short"></span>
                                                <span class="b-checkbox__line b-checkbox__line--long"></span>
                                            </span>
                                            <span class="b-checkbox__text">
                                                <?= $subSection['NAME']; ?>
                                            </span>
                                        </label>
                                    </div>
                                    <a href="javascript:void(0);" data-price-eye class="lk-p-list__look">
                                        <svg class="i-icon">
                                            <use xlink:href="#icon-watch"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                        <?php } ?>

                    </div>

                <?php endforeach; ?>

            </div>

            <?php } ?>
        </div>

    <?php endforeach; ?>

    <div class="b-form__submit">
        <button class="btn btn--transparent btn--big"
                type="submit">
            Сформировать прайс-лист
        </button>
    </div>

</form>
<?php endif ?>

