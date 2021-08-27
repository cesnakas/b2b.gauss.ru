<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (empty($arResult["ALL_ITEMS"]))
	return;

CJSCore::Init();

foreach ($arResult['ALL_ITEMS'] as $item){?>
	<a href="<?=$item['LINK']?>"><?=mb_strtoupper($item['TEXT']);?></a>
<?}