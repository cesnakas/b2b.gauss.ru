<?
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;

/**
 * Идентификатор модуля
 */
$module_id  = 'citfact.datacache';
global $APPLICATION;

$moduleAccessLevel = $APPLICATION->GetGroupRight($module_id);
if ($moduleAccessLevel < 'R')
{
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

/**
 * Подключаем модуль (выполняем код в файле include.php)
 */
//CModule::IncludeModule( $module_id );

/**
 * Языковые константы (файл lang/ru/options.php)
 */
global $MESS,$REQUEST_METHOD;
IncludeModuleLangFile( __FILE__ );


/**
 * сохраняем
 */
$Update = $_POST['Update'];
if($REQUEST_METHOD == 'POST' && !empty($Update) && $moduleAccessLevel == "W") {
//    Option::set($module_id, "pay", $pay);
}

/**
 * Описываем табы административной панели битрикса
 */
$aTabs = array(
    array(
        'DIV'   => 'citfact.datacache.edit',
        'TAB'   => Loc::getMessage('MAIN_TAB_SET'),
        'TITLE' => Loc::getMessage('MAIN_TAB_TITLE_SET' )
    ),
    array(
        "DIV" => "citfact.datacache.rights",
        "TAB" => Loc::getMessage("MAIN_TAB_RIGHTS"),
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS")
    )
);

/**
 * Инициализируем табы
 */
$oTabControl = new CAdmintabControl( 'tabControl', $aTabs );
$oTabControl->Begin();

/**
 * Ниже пошла форма страницы с настройками модуля
 */
?><form method="POST" enctype="multipart/form-data" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars( $module_id )?>&lang=<?echo LANG?>">
    <?=bitrix_sessid_post()?>

    <?$oTabControl->BeginNextTab();?>
        <tr>
            <td>
                <b><?=Loc::getMessage('CITFACT_OPTIONS')?></b>
            </td>
        </tr>

    <?$oTabControl->BeginNextTab();?>
        <?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");?>

    <?$oTabControl->Buttons();?>
<!--        <input type="submit"--><?//=($moduleAccessLevel < 'W' ? ' disabled' : ''); ?><!-- name="Update" value="--><?//=Loc::getMessage('CITFACT_OPTIONS_BTN_SAVE')?><!--" class="adm-btn-save" title="--><?//=Loc::getMessage('CITFACT_OPTIONS_BTN_SAVE'); ?><!--">-->
        <input type="submit"<?=($moduleAccessLevel < 'W' ? ' disabled' : ''); ?> name="Cancel" value="<?=Loc::getMessage('CITFACT_OPTIONS_BTN_CANCEL')?>" class="adm-btn-save" title="<?=Loc::getMessage('CITFACT_OPTIONS_BTN_CANCEL'); ?>">
    <?$oTabControl->End();?>
</form>