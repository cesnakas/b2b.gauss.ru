<?

use Bitrix\Main\Application;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Лист ожидания");

global $USER;
if ($USER->IsAuthorized() === false) {
    LocalRedirect('/personal/');
}
?>
<div>
    <?
    $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        array(
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => "/local/include/areas/personal/list-wait/description.php"
        )
    );
    ?>
</div>
<?
$APPLICATION->IncludeComponent(
    "citfact:list.wait",
    ".default",
    [
        "USER_ID" => $USER->GetID(),
    ],
    false
); ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
