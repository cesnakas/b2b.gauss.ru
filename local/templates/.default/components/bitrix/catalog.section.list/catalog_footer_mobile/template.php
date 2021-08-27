<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
?>
    <div class="m-menu m-menu--catalog" data-m-menu="catalog">
        <?foreach ($arResult['SECTIONS'] as $arSection){
            $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
            $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams); ?>
            <a href="<?=$arSection['SECTION_PAGE_URL']?>" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
                <?=$arSection['NAME']?>
            </a>
        <?}?>
        <?$APPLICATION->IncludeComponent("bitrix:menu", "top_promotion_mobile", [
            "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
            "CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
            "DELAY" => "N",	// Откладывать выполнение шаблона меню
            "MAX_LEVEL" => "1",	// Уровень вложенности меню
            "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
            "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
            "MENU_CACHE_TYPE" => "A",	// Тип кеширования
            "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
            "MENU_THEME" => "green",	// Тема меню
            "ROOT_MENU_TYPE" => "top_promotion",	// Тип меню для первого уровня
            "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
            "COMPONENT_TEMPLATE" => "top_promotion",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "STATIC",
        ],
            false
        );?>
        <?global $USER;
        if ($USER->IsAuthorized()) {
            $hrefFastOrder = '/local/include/modals/fast_order.php';
        } else {
            $hrefFastOrder = '/local/include/modals/auth.php';
        } ?>
        <a href="<?=$hrefFastOrder;?>" class="h-fast-order" data-modal="ajax" title="БЫСТРЫЙ ЗАКАЗ">
            БЫСТРЫЙ ЗАКАЗ
        </a>
    </div>

