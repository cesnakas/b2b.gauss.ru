<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Citfact\SiteCore\Core;

$core = Core::getInstance();

if (empty($arResult["ALL_ITEMS"]))
	return;

CJSCore::Init();
?>
<? foreach ($arResult['ALL_ITEMS'] as $key => $item) : ?>
    <a href="<?=$item['LINK']?>" title="<?=$item['TEXT']?>"><?=$item['TEXT']?></a>
<? endforeach; ?>