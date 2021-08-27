<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Citfact\Sitecore\Core;

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
$this->setFrameMode(true);
global $APPLICATION;

$core = Core::getInstance();

$bxajaxid = CAjax::GetComponentID($component->__name, $component->__template->__name, $component->arParams['AJAX_OPTION_ADDITIONAL']);
?>
<div class="b-tabs__content">
    <div class="b-tabs__item active">
        <div class="blocks">
            <div class="blocks__top">
                <a href="/local/include/modals/souvenirs-request.php" class="btn btn--transparent" data-modal="marketing">
                    Отправить заявку
                </a>
                <p>Вы можете добавить в заявку любое количество позиций</p>
            </div>
            <div class="blocks__inner blocks__inner--souvenirs" id="block_<?=$bxajaxid?>">
                <? foreach ($arResult['ITEMS'] as $key => $arItem):
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <div class="blocks__card" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                        <a href="<?= $arItem['PREVIEW_PICTURE']['SRC']['SCREEN']; ?>" data-modal="image" class="blocks__img">
                            <img data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']['ORIGIN']?>"
                                 src="<?= $key < 5 ? $arItem['PREVIEW_PICTURE']['SRC']['PREVIEW'] : $core::IMAGE_PLACEHOLDER_TRANSPARENT ?>"
                                 alt='<?= $arItem['PREVIEW_PICTURE']['ALT'] ?>'
                                 title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>"
                                 class="lazy">
                        </a>
                        <div class="blocks__title"><?= $arItem['NAME'] ?></div>
                        <div class="blocks__text"><?= $arItem['PREVIEW_TEXT'] ?></div>
                        <div class="b-checkbox">
                            <label class="b-checkbox__label">
                                <input type="checkbox" id="checkbox" class="b-checkbox__input"
                                       data-id="<?= $arItem['ID']; ?>">
                                            <span class="b-checkbox__box">
                                            <span class="b-checkbox__line b-checkbox__line--short"></span>
                                            <span class="b-checkbox__line b-checkbox__line--long"></span>
                                        </span>
                                <span class="b-checkbox__text">Добавить в заявку</span>
                            </label>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
            <? if($arResult["NAV_RESULT"]->nEndPage > 1 && $arResult["NAV_RESULT"]->NavPageNomer<$arResult["NAV_RESULT"]->nEndPage):?>
                <div class="blocks__bottom" id="btn_<?=$bxajaxid?>">
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
            <?endif?>
        </div>
    </div>
</div>
