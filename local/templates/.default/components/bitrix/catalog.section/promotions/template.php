<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */

$this->setFrameMode(true);

if (!empty($arResult['NAV_RESULT']))
{
    $navParams =  array(
        'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
        'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
        'NavNum' => $arResult['NAV_RESULT']->NavNum
    );
}
else
{
    $navParams = array(
        'NavPageCount' => 1,
        'NavPageNomer' => 1,
        'NavNum' => $this->randString()
    );
}

$showTopPager = false;
$showBottomPager = false;

if ($arParams['PAGE_ELEMENT_COUNT'] > 0 && $navParams['NavPageCount'] > 1)
{
    $showTopPager = $arParams['DISPLAY_TOP_PAGER'];
    $showBottomPager = $arParams['DISPLAY_BOTTOM_PAGER'];
}

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

if ( !empty($arResult['ITEMS']) ) {?>
    <div class="title-1">
        <span>Товары участвующие в акции</span>
    </div>

    <div class="p-slider slider">
        <div class='swiper-container' data-slider='p-2' data-slides="5"> <? /* сейчас инициализируется вручную, вынести в ajax success */ ?>
            <div class='swiper-wrapper'>
                <?
                $areaIds = array();
                $i = 0;
                foreach ($arResult['ITEMS'] as $key => $item) {
                    $uniqueId = $item['ID'] . '_' . md5($this->randString() . $component->getAction());
                    $areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
                    $this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
                    $this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);
                    ?><? $APPLICATION->IncludeComponent(
                        'citfact:html.catalog.item',
                        'promotions',
                        [
                            'ITEM' => $item,
                            'KEY' => $key,
                            'COUNT' => $i,
                            'ON_FIRST' => 5,
                            'PRICE_CODE' => $arParams['PRICE_CODE'],
                            'AREA_ID' => $areaIds[$item['ID']],
                            'PRODUCT_CLASS' => '',
                            'PRODUCT_IMAGE_SIZE' => array(215, 215),
                            'SECTION' => array('PATH' => $arResult['PATH']),
                            'DISPLAY_PROPERTIES_SETTINGS' => $arResult['DISPLAY_PROPERTIES_SETTINGS'][$item['IBLOCK_SECTION_ID']] ?: [],
                            'IS_USER_AUTHORIZED' => $arParams['IS_USER_AUTHORIZED'],
                        ],
                        $component,
                        ['HIDE_ICONS' => 'Y']
                    ) ?>
                    <?
                    $i++;
                }
                ?>
            </div>
        </div>
        <div class='slider__arrows'>
            <div class='slider__arrow slider__arrow--prev' data-slider-arrow-p="p-2">
                <svg class='i-icon'>
                    <use xlink:href='#icon-arrow-r'/>
                </svg>
            </div>
            <div class='slider__arrow slider__arrow--next' data-slider-arrow-n="p-2">
                <svg class='i-icon'>
                    <use xlink:href='#icon-arrow-r'/>
                </svg>
            </div>
        </div>
    </div>
    
    <?
}

$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedTemplate = $signer->sign($templateName, 'catalog.section');
$signedParams = $signer->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'catalog.section');
?>