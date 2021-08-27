<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Citfact\SiteCore\Core;

$core = Core::getInstance();

if (empty($arResult["ALL_ITEMS"]))
	return;

CJSCore::Init();
?>
<div class="f__item">
<?  $i = 0;
    foreach ($arResult['ALL_ITEMS'] as $key => $item) : ?>
    <?if ($i < 5):?>
        <a href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
        <?unset($arResult['ALL_ITEMS'][$key]);?>
    <?endif;
    $i++;
    endforeach; ?>
</div>

<div class="f__item">
    <? foreach ($arResult['ALL_ITEMS'] as $key => $item) : ?>
            <a href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
    <? endforeach; ?>
</div>