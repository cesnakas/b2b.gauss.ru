<? setlocale(LC_ALL, 'ru_RU.utf8');

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
CModule::IncludeModule('iblock');
IncludeModuleLangFile(__FILE__);
CJSCore::Init(['jquery']);

use Citfact\SiteCore\Core;


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");


$core = Core::getInstance();
$iblockId =  $core->getIblockId($core::IBLOCK_CODE_CATALOG);

//Получаем все свойства инфоблока каталог
$rsProperty = \Bitrix\Iblock\PropertyTable::getList(array(
    'filter' => array('IBLOCK_ID'=>$iblockId,'ACTIVE'=>'Y'),
));
$arAllProps = [];
while($arProperty=$rsProperty->fetch())
{
    $arAllProps[] = $arProperty;
}

//Получаем массив id свойств, которые отображены на детальной странице
$res = \Bitrix\Iblock\Model\PropertyFeature::getDetailPageShowProperties($iblockId);
if(check_bitrix_sessid()) {
    // Здесь обработка форм
    //Включаем свойство на деталке
    foreach ($_POST['property']as $key=>$prop) {
        if($prop =='Y' && !in_array($res, $key) ) {
            //Если чекбокс отмечен и не в массиве $res,делаем активным свойство на детальной странице
            \Bitrix\Iblock\Model\PropertyFeature::setFeatures(
                $key, [[
                    "MODULE_ID" => "iblock",
                    "IS_ENABLED" => "Y",
                    "FEATURE_ID" => "DETAIL_PAGE_SHOW"
                ]]
            );
        }
    }
        //Отключаем свойство на деталке
        //Сейчас Id выступают ключами, поэтому собираем отдельный массив ключей и сравниваем с массивом $res
        $keys = array_keys($_POST['property']);
        $diff = array_diff($res, $keys);
        //Каждое свойство с id из массива $diff отключаем с детальной страницы
        foreach ($diff as $key=>$val){
            \Bitrix\Iblock\Model\PropertyFeature::setFeatures(
                $val, [[
                "MODULE_ID" => "iblock",
                "IS_ENABLED" => "N",
                "FEATURE_ID" => "DETAIL_PAGE_SHOW"
            ]]
            );
        }
        //переопределяем массив $res,чтобы сохранить данные
    $res = \Bitrix\Iblock\Model\PropertyFeature::getDetailPageShowProperties($iblockId);
}

$aTabs = array(
    array("DIV" => "edit1", "TAB" => "Настройки выгрузки свойств", "TITLE" => "Настройки выгрузки свойств"),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);
?>

<?if (!empty($arErrors)):?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title">Ошибка</div>
            <?foreach($arErrors as $error):?>
                <?=$error?><br />
            <?endforeach?>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
<?endif?>

<?$tabControl->Begin();?>

<?$tabControl->BeginNextTab();?>
<tr><td>
        <form method="post" action="<?echo $APPLICATION->GetCurPage()?>" enctype="multipart/form-data" name="post_form" id="post_form">
            <?echo bitrix_sessid_post();?>
            <?foreach ($arAllProps as $prop){?>
                <label><input type="checkbox"
                              id="<?=$prop['CODE']?>"
                        <?= (in_array($prop['ID'], $res)) ? 'checked' : ''; ?>
                              name="property[<?=$prop['ID']?>]" value="Y"> <?=$prop['NAME']?> </label>
                <br/><br/>
            <?}?>
            <input class="adm-btn" type="submit" name="Update_tab1" value="Применить" title="Применить">
        </form>
    </td></tr>
<?$tabControl->Buttons();?>
<?$tabControl->End();?>

<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin_after.php");?>
