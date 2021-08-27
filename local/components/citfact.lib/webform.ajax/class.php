<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Citfact\SiteCore\Tools\DataAlteration;
use Citfact\SiteCore\UserDataManager\UserDataManager;

class WebFormAjaxComponent extends \CBitrixComponent
{
    private static $dropDownData = [
        'COMPANY_TYPE' => [
            '- Выберите из списка -',
            'Интернет-магазин',
            'Клуб',
            'Тренер',
            'Другой',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        $form = new CForm();
        CJSCore::Init();

        $this->arResult = array();

        $this->arParams['WEB_FORM_ID'] = $form->GetDataByID($this->arParams['WEB_FORM_ID'],
            $this->arResult['arForm'],
            $this->arResult['arQuestions'],
            $this->arResult['arAnswers'],
            $this->arResult['arDropDown'],
            $this->arResult['arMultiSelect'],
            $this->arResult['bAdmin'] == 'Y' ||
            $this->arParams['SHOW_ADDITIONAL'] == 'Y' ||
            $this->arParams['EDIT_ADDITIONAL'] == 'Y' ? 'ALL' : 'N'
        );

        $this->arResult['WEB_FORM_NAME'] = $this->arResult['arForm']['SID'];
        $this->arResult['SUCCESS_MESSAGE'] = $this->arParams['SUCCESS_MESSAGE'];
        $this->ajax();

        $this->arResult['QUESTIONS'] = array();

        reset($this->arResult['arQuestions']);
        foreach ($this->arResult['arQuestions'] as $keyQuestion => $arQuestion) {
            $fieldSid = $arQuestion['SID'];

            $this->arResult['QUESTIONS'][$fieldSid] = array(
                'CAPTION' =>
                    $this->arResult['arQuestions'][$fieldSid]['TITLE_TYPE'] == 'html' ?
                        $this->arResult['arQuestions'][$fieldSid]['TITLE'] :
                        nl2br(htmlspecialcharsbx($this->arResult['arQuestions'][$fieldSid]['TITLE'])),

                'IS_HTML_CAPTION' => $this->arResult['arQuestions'][$fieldSid]['TITLE_TYPE'] == 'html' ? 'Y' : 'N',
                'REQUIRED' => $this->arResult['arQuestions'][$fieldSid]['REQUIRED'] == 'Y' ? 'Y' : 'N',
                'IS_INPUT_CAPTION_IMAGE' => intval($this->arResult['arQuestions'][$fieldSid]['IMAGE_ID']) > 0 ? 'Y' : 'N',
                'COMMENTS' => $this->arResult['arQuestions'][$fieldSid]['COMMENTS'],
                'VARNAME' => $arQuestion['VARNAME'],
            );

            $this->arResult['QUESTIONS'][$fieldSid]['HTML_CODE'] = array();

            foreach ($this->arResult['arAnswers'][$fieldSid] as $keyAnswer => $answer) {

                switch ($arQuestion['SID']) {
                    case 'START_DATE':
                    case 'DATE':
                    case 'DATE_FROM':
                    case 'DATE_TO':
                        $this->arResult['arAnswers'][$fieldSid][$keyAnswer]['FIELD_TYPE'] = 'date';
                        $answer['FIELD_TYPE'] = 'date';
                        break;
                }

                switch ($answer['FIELD_TYPE']) {
                    case 'text':
                        $this->arResult['QUESTIONS'][$fieldSid]['NAME'] = 'form_' . $answer['FIELD_TYPE'] . '_' . $answer['ID'];

                        $value = '';
                        $dataAttributes = ['data-f-field'];
                        $class = '';

                        if ($arQuestion['REQUIRED'] == 'Y') {
                            $dataAttributes[] = 'data-required="Y"';
                        }

                        if (is_int(stripos($keyQuestion, 'email'))) {
                            $dataAttributes[] = 'data-form-field-email';
                        }

                        if (is_int(stripos($keyQuestion, 'phone'))) {
                            $dataAttributes[] = 'data-form-field-phone';
                            $dataAttributes[] = 'data-mask="phone"';
                        }

                        $addParams = 'class="' . $class . '" ' . implode(' ', $dataAttributes);

                        if ('Y' === $this->arParams['SET_PLACEHOLDER']) {
                            $isRequired = 'Y' === $arQuestion['REQUIRED'] ? '*' : '';

                            $addParams .= ' placeholder="' . $arQuestion['TITLE'] . $isRequired . '"';
                        }


                        $htmlCodeField = $form->GetTextField(
                            $answer['ID'],
                            $value,
                            1,
                            $addParams
                        );

                        $this->arResult['QUESTIONS'][$fieldSid]['HTML_CODE'][] = $htmlCodeField;
                        break;

                    case 'radio':

                        $dataAttributes = [];

                        if ($arQuestion['REQUIRED'] == 'Y') {
                            $dataAttributes[] = 'data-required="GROUP"';
                        }

                        $dataAttributes = implode(' ', $dataAttributes);

                        $inputContainer[] = '<div class="b-checkbox b-checkbox--radio">
                                                <label class="b-checkbox__label">
                                                    <input type="radio" 
                                                    id="form_radio_' . $answer['ID'] . '"
                                                    name="form_radio_' . $fieldSid . '"
                                                    value="' . $answer['ID'] . '"
                                                    class="b-checkbox__input"
                                                    ' . $dataAttributes . '>
                                                    <span class="b-checkbox__box"></span>
                                                    <span class="b-checkbox__text">' . $answer['MESSAGE'] . '</span>
                                                </label>
                                            </div>';
                        $resDropdown = implode('', $inputContainer);

                        $this->arResult['QUESTIONS'][$fieldSid]['HTML_CODE'][0] = $resDropdown;
                        break;

                    case 'textarea':

                        $this->arResult['QUESTIONS'][$fieldSid]['NAME'] = 'form_' . $answer['FIELD_TYPE'] . '_' . $answer['ID'];

                        $dataAttributes = ['data-f-field'];

                        if ($arQuestion['REQUIRED'] == 'Y') {
                            $dataAttributes[] = 'data-required="Y"';
                        }

                        $addParams = implode(' ', $dataAttributes);

                        $res = '<textarea maxlength="400" name="form_textarea_' . $answer['ID'] . '" '.$addParams.'></textarea>';

                        $this->arResult['QUESTIONS'][$fieldSid]['HTML_CODE'][] = $res;
                        break;

                    case 'file':
                        $dataAttributes = [];

                        if ($arQuestion['REQUIRED'] == 'Y') {
                            $dataAttributes[] = 'data-file-field-required="Y"';
                        }

                        $dataAttributes = implode(' ', $dataAttributes);

                        //$res = '<input name=\'form_file_' . $answer['ID'] . '\' value=\'Прикрепить файл\' ' . $dataAttributes . ' type=\'file\'>';

                        $res = '<div class="b-form__upload">
                    <label>
                        <input name="form_file_' . $answer['ID'] . '" data-file-upload="add" ' . $dataAttributes . ' type="file"
                        accept=".xls,.xlsx,.doc,.docx,.pdf,.jpg,.png,.bmp,.jpeg">
                        <span id="cross" class="marker btn btn--grey" data-file-text>
                            <svg class="i-icon">
                                <use xlink:href="#icon-attachment"/>
                            </svg>
                            <span>Выбрать файл</span>
                        </span>
                    </label>
                    <span id="btn-del" data-btn-delete class="plus plus--cross hidden"></span>
                    <span data-file-load>Файл не выбран</span>
                </div><div data-field-errors></div>';
                        $notFirst = true;
                        $this->arResult['QUESTIONS'][$fieldSid]['HTML_CODE'][] = $res;
                        break;

                    case 'dropdown':
                        $dataAttributes = [];

                        if ($arQuestion['REQUIRED'] == 'Y') {
                            $dataAttributes[] = 'data-required="Y"';
                        }

                        $dataAttributes = implode(' ', $dataAttributes);

                        $arDropDown = [];
                        if (self::$dropDownData[$fieldSid]) {
                            $arDropDown['reference'] = self::$dropDownData[$fieldSid];
                            for ($i = 0; $i < count($arDropDown['reference']); $i++) {
                                $arDropDown['reference_id'][] = $i;
                            }
                        }

                        $arOptions = [];
                        $counter = 0;

                        foreach ($this->arResult['arAnswers'][$fieldSid] as $arAnswer) {
                            $counter++;

                            if ($counter === 1) {
                                $arAnswer['ID'] = 0;
                            }

                            $arOptions[] = '<option value="'.$arAnswer['ID'].'">'.$arAnswer['MESSAGE'].'</option>';
                        }


                        if ($arQuestion['SID'] !== $lastSidForm || !$arOptions) {
                            $resDropdown = '<select data-f-field name="form_dropdown_'. $arQuestion['VARNAME'] .'" '. $dataAttributes .'>'.implode('', $arOptions).'</select>';
                        }

                        $lastSidForm = $arQuestion['SID'];

                        $this->arResult['QUESTIONS'][$fieldSid]['HTML_CODE'][0] = $resDropdown;
                        break;

                    case 'hidden':
                        $value = $form->GetHiddenValue($answer['ID'], $answer, $this->arResult['arrVALUES']);

                        $res = $form->GetHiddenField(
                            $answer['ID'],
                            $value,
                            ($fieldSid == 'SESSION_ID') ? 'data-session-id' : ''
                        );

                        $this->arResult['QUESTIONS'][$fieldSid]['HTML_CODE'][] = $res;
                        break;

                    case 'date':
                        $this->arResult['QUESTIONS'][$fieldSid]['NAME'] = 'form_text_' . $answer['ID'];

                        $dataAttributes = ['data-f-field'];

                        if ($arQuestion['REQUIRED'] == 'Y') {
                            $dataAttributes[] = 'data-required="Y"';
                        }

                        $dataAttributes[] = 'data-mask="date"';
                        $dataAttributes[] = 'data-min-view="months"';
                        $dataAttributes[] = 'data-view="months"';
                        $dataAttributes[] = 'data-date-format="MM yyyy"';
                        $dataAttributes[] = 'autocomplete="off"';

                        $addParams = implode(' ', $dataAttributes);
                        $res = '<input type="text" name="form_text_' . $answer['ID'] . '" '.$addParams.'>';

                        $this->arResult['QUESTIONS'][$fieldSid]['HTML_CODE'][] = $res;
                        break;

                    default:
                        break;
                }
            }

            $this->arResult['QUESTIONS'][$fieldSid]['HTML_CODE'] = implode(
                $this->arParams['IMPLODE_BR'] != 'N' ? '<br>' : '',
                $this->arResult['QUESTIONS'][$fieldSid]['HTML_CODE']);
            $this->arResult['QUESTIONS'][$fieldSid]['STRUCTURE'] = $this->arResult['arAnswers'][$fieldSid];
            $this->arResult['QUESTIONS'][$fieldSid]['VALUE'] = '';
        }

        $this->arResult = array_merge(
            $this->arResult,
            array(
                'FORM_TITLE' => trim(htmlspecialcharsbx($this->arResult['arForm']['NAME'])), // form title
                'FORM_DESCRIPTION' =>
                    $this->arResult['arForm']['DESCRIPTION_TYPE'] == 'html' ?
                        trim($this->arResult['arForm']['DESCRIPTION']) :
                        nl2br(htmlspecialcharsbx(trim($this->arResult['arForm']['DESCRIPTION']))),
                'isFormTitle' => strlen($this->arResult['arForm']['NAME']) > 0 ? 'Y' : 'N', // flag 'does form have title'
                'isFormDescription' => strlen($this->arResult['arForm']['DESCRIPTION']) > 0 ? 'Y' : 'N', // flag 'does form have description'
            )
        );

        global $APPLICATION;
        $this->arResult['CAPTCHA_CODE'] = $APPLICATION->CaptchaGetCode();

        $this->IncludeComponentTemplate();
    }

    public function ajax()
    {
        global $USER;

        // получаем контрагента и ассистента
        $contragentXml = UserDataManager::getUserContragentXmlID();

        $dataAlteration = new DataAlteration();
        $form = new CForm();
        $formResult = new \CFormResult();
        $this->arResult['RETURN'] = [
            'errors' => [],
            'result' => [],
            'debug' => []
        ];
        $this->arResult['USER_ID'] = $USER->GetID();
        $application = Application::getInstance();
        $request = $application->getContext()->getRequest();
        $postData = $dataAlteration->requestSpecialChars($request->getPostList()->toArray());

        if (
            !$request->isPost() ||
            !check_bitrix_sessid() ||
            $postData['WEB_FORM_CODE'] !== $this->arParams['WEB_FORM_CODE']
        ) {
            return;
        }

        $res = $form->GetList($by = 'ID', $order = 'ASC', ['ID' => $this->arParams['WEB_FORM_ID']], $isFiltered);
        $this->arResult['WEB_FORM'] = $res->Fetch();
        $this->arResult['OUTPUT_NUMBER_DATA_KEY'] = '';
        $this->arResult['RETURN']['debug']['errors'] = $form->Check(
            $this->arParams['WEB_FORM_ID'],
            $postData,
            false,
            'Y',
            'Y'
        );

        $resultId = $formResult->Add($this->arParams['WEB_FORM_ID'], $postData, 'N');
        $this->arResult['SUCCESS'] = true;
        $this->arResult['ID_RESULT'] = $resultId;

        CFormCRM::onResultAdded($this->arParams['WEB_FORM_ID'], $resultId);
        $formResult->SetEvent($resultId);
        $formResult->Mail($resultId);

        if ($this->arParams['RETURN_FORM'] == 'Y') {
            return;
        }

        $this->IncludeComponentTemplate();
        die;
    }

    /**
     * @param $arParams
     * @return array
     * @throws Exception
     */

    public function onPrepareComponentParams($arParams)
    {
        Loader::includeModule('form');
        $form = new CForm();

        if (!$arParams['WEB_FORM_ID']) {
            $obForm = $form->GetList($by = 'ID', $order = 'ASC', ['SID' => $arParams['WEB_FORM_CODE']], $isFiltered);

            if ($arForm = $obForm->Fetch()) {
                $arParams['WEB_FORM_ID'] = $arForm['ID'];
            }
        }

        return $arParams;
    }

}
