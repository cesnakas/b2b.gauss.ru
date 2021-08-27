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
$bxajaxid = CAjax::GetComponentID($component->__name, $component->__template->__name, $component->arParams['AJAX_OPTION_ADDITIONAL']);

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
$maxItems = 20;

if ($arParams['PAGE_ELEMENT_COUNT'] > 0 && $navParams['NavPageCount'] > 1)
{
	$showTopPager = $arParams['DISPLAY_TOP_PAGER'];
	$showBottomPager = $arParams['DISPLAY_BOTTOM_PAGER'];
}

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
?>

<?if(!$_GET["bxajaxid"]):?>
    <div class="lk__favorite" data-favourites-container>
<?endif?>

    <?
    if ( !empty($arResult['ITEMS']) )
    {
        $areaIds = array();
        foreach ($arResult['ITEMS'] as $item)
        {
            $uniqueId = $item['ID'].'_'.md5($this->randString().$component->getAction());
            $areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
            $this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
            $this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);

            ?><? $APPLICATION->IncludeComponent(
            'citfact:html.catalog.item',
            'product_table',
            array(
                'ITEM' => $item,
                'AREA_ID' => $areaIds[$item['ID']],
                'PRICE_CODE' => $arParams['PRICE_CODE'],
                'PRODUCT_CLASS' => '',
                'PRODUCT_IMAGE_SIZE' => array(215, 215),
                'SECTION' => array('PATH' => $arResult['PATH']),
                'IS_USER_AUTHORIZED' => $arParams['IS_USER_AUTHORIZED'],
                'IS_PAGE_FAVOURITE' => 'Y',
            )
            )?>
        <?
        }
    }
    else
    {?>

    <?}?>

    <div class="c__bottom" id="btn_<?= $bxajaxid ?>">
        <? if ($showBottomPager && $arResult["NAV_RESULT"]->nEndPage > 1 && $arResult["NAV_RESULT"]->NavPageNomer < $arResult["NAV_RESULT"]->nEndPage): ?>
            <a href="javascript:void(0)" data-ajax-id="<?= $bxajaxid ?>"
               class="btn btn--loading btn--orange"
               data-show-more="<?= $arResult["NAV_RESULT"]->NavNum ?>"
               data-next-page="<?= ($arResult["NAV_RESULT"]->NavPageNomer + 1) ?>"
               data-max-page="<?= $arResult["NAV_RESULT"]->nEndPage ?>">
                <svg class='i-icon'>
                    <use xlink:href='#icon-loading'/>
                </svg>
                <span>Загрузить ещё</span>
                <span>Загружается</span>
            </a>
        <? endif ?>
    </div>
<?if(!$_GET["bxajaxid"]):?>

    </div>
<?endif?>

<?$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedTemplate = $signer->sign($templateName, 'catalog.section');
$signedParams = $signer->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'catalog.section');
?>
