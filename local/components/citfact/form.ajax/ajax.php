<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
use Bitrix\Sale\Internals;

Loader::includeModule('iblock');

global $USER;
$arReturn = array('errors' => array(), 'result' => array());

$arFormdata = $_POST;

$arReturn['debug']['FormData'] = $arFormdata;

if ($arFormdata['yarobot'] == '' && check_bitrix_sessid()) {

    $iblockId = (int)$arFormdata['iblockId'];
    $arParams = (array)json_decode($arFormdata['arParams']); // Массив со вложенными объектами

    // Массив свойств из инфоблока
    $arPropsIblock = array();
    $properties = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array("ACTIVE" => "Y", "IBLOCK_ID" => $iblockId));
    while ($prop_fields = $properties->GetNext()) {
        $arPropsIblock[$prop_fields['CODE']] = $prop_fields;
    }

    $hasFiles = false;
    $arPropVals = array();
    foreach ($arParams['SHOW_PROPERTIES'] as $key => $arProp) {
        // Для свойства типа «Файл» формируем массив
        if (($arPropsIblock[$key]['PROPERTY_TYPE'] == 'F') && isset($_FILES[$key]) && is_array($_FILES[$key])) {
            $files = $_FILES[$key];

            if (is_array($files['name'])) {
                foreach ($files['name'] as $fid => $file) {
                    // название файла
                    $fileName = basename($file);
                    // временный файл
                    $tmpFile = $files['tmp_name'][$fid];
                    // перемещение файла
                    $newFile = $_SERVER['DOCUMENT_ROOT'] . $arParams['AJAX_FILES_PATH'] . $fileName;
                    move_uploaded_file($tmpFile, $newFile);

                    $arPropVals[$key]['n' . $fid] = array(
                        'VALUE' => CFile::MakeFileArray($newFile)
                    );
                }
            } else {
                // название файла
                $fileName = basename($files['name']);
                // временный файл
                $tmpFile = $files['tmp_name'];
                // перемещение файла
                $newFile = $_SERVER['DOCUMENT_ROOT'] . $arParams['AJAX_FILES_PATH'] . '/' . $fileName;
                move_uploaded_file($tmpFile, $newFile);

                $arPropVals[$key] = CFile::MakeFileArray($newFile);
            }

            $hasFiles = true;
        } elseif (is_array($arFormdata[$key])) { // список
            $arPropVals[$key] = $arFormdata[$key];
        } else {
            $arPropVals[$key] = htmlspecialcharsbx(trim($arFormdata[$key]));
        }
    }

    $el = new \CIBlockElement;

    // Ищем совпадения по свойствам
    $equal_element_id = 0;
    if (!empty($arParams['CHECK_EQUAL_PROPS'])) {
        $arOrder = array();
        $arFilter = array('IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y');
        foreach ($arParams['CHECK_EQUAL_PROPS'] as $propcode) {
            $arFilter['PROPERTY_' . $propcode] = $arPropVals[$propcode];
        }
        $arSelectFields = array("ID");
        $rsElements = $el->GetList($arOrder, $arFilter, FALSE, FALSE, $arSelectFields);
        if ($arElement = $rsElements->GetNext()) {
            $equal_element_id = $arElement['ID'];
        }
    }

    // Если был найден совпадающий элемент, то обновляем его
    // Иначе, добавляем новый
    if ($equal_element_id != 0) {
        $el->SetPropertyValuesEx($equal_element_id, false, $arPropVals);
        $arReturn['result']['success'] = 'Y';
    } else {
        if (isset($arFormdata["NAME"]) && !empty($arFormdata["NAME"])) {
            $name = htmlspecialchars($arFormdata["NAME"], ENT_NOQUOTES | ENT_HTML401);
        } else {
            $name = 'Запрос';
        }

        $arLoadProductArray = Array(
            "MODIFIED_BY" => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID" => $iblockId,
            "NAME" => $name,
            "PREVIEW_TEXT" => $arFormdata['PREVIEW_TEXT'],
            "ACTIVE" => ($arParams['ELEMENT_ACTIVE'] == 'Y' ? 'Y' : 'N'),
            "DATE_ACTIVE_FROM" => ConvertTimeStamp(time(), "FULL"),
            "PROPERTY_VALUES" => $arPropVals
        );

        // Добавляем в инфоблок
        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            $arReturn['result']['success'] = 'Y';
            $arReturn['result']['message'] = $arParams['~SUCCESS_MESSAGE'];

            // Если есть файлы, прикрепляем их в письмо
            $arFilesIds = array();
            if ($hasFiles === true && $arParams['ATTACH_FILES'] == 'Y') {
                $arOrder = array();
                $arFilter = array('IBLOCK_ID' => $iblockId, 'ID' => $PRODUCT_ID);
                $arSelectFields = array("ID", "ACTIVE", "NAME", 'PROPERTY_' . $arParams['FILE_PROPERTY_CODE']);
                $rsElements = CIBlockElement::GetList($arOrder, $arFilter, FALSE, FALSE, $arSelectFields);
                while ($arElement = $rsElements->GetNext()) {
                    $arFilesIds[] = $arElement['PROPERTY_' . $arParams['FILE_PROPERTY_CODE'] . '_VALUE'];
                }
            }

            // Отсылаем письмо
            $arFields = $arPropVals;
            $arFields['NAME'] = $name;
            $arFields['ELEMENT_ID'] = $PRODUCT_ID;
            $arFields['IBLOCK_ID'] = $IBLOCK_ID;
            $arFields['PREVIEW_TEXT'] = $arLoadProductArray['PREVIEW_TEXT'];

            $isSent = false;
            if (!empty($arParams["EVENT_MESSAGE_ID"]) && $arParams["EVENT_NAME"]) {
                foreach ($arParams["EVENT_MESSAGE_ID"] as $v)
                    if (IntVal($v) > 0) {
                        CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arFields, "N", IntVal($v));
                        $isSent = true;
                    }
            }
            if (!$isSent && $arParams["EVENT_NAME"]) {
                CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arFields, 'Y', '', $arFilesIds);
                $arReturn['EVENT_NAME'] = array($arParams["EVENT_NAME"], SITE_ID, $arFields, 'Y', '', $arFilesIds);
            }
        } else {
            $arReturn['errors'][] = "Ошибка: " . $el->LAST_ERROR;
        }
    }
}

$strReturn = json_encode($arReturn);
echo $strReturn;


require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
