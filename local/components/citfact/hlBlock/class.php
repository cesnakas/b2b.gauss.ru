<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Citfact\SiteCore\Core;
use Citfact\Sitecore\UserDataManager;

class MainProfileComponent extends CBitrixComponent implements Controllerable
{
    /** Флаг использования ajax-запроса
     * @var bool
     */
    public $isAjax = false;

    /**
     * Флаг обработки данных после ajax-запроса
     * @var bool
     */
    private $isSuccess = false;

    private $arAddresses;
    public $arContragent;

    /**
     * @var array
     */
    protected $request;

    public function __construct(CBitrixComponent $component = null)
    {
        parent::__construct($component);

        Loc::loadMessages(__FILE__);
    }

    /**
     * Проверяет и подготавливает все переданные в компонент параметры.
     * Все действия по модификации $arParams здесь
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams)
    {

        return $arParams;
    }

    /**
     * Настройки для action
     * @return array
     */
    public function configureActions()
    {
        return [
            'hlBlock' => [
                'prefilters' => [
                    new ActionFilter\Authentication(),
                    new ActionFilter\HttpMethod(
                        [
                            ActionFilter\HttpMethod::METHOD_GET,
                            ActionFilter\HttpMethod::METHOD_POST
                        ]
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ]
        ];
    }

    /** Изменят данные пользователя
     * Вызывается ajax запросом
     * @return mixed
     */
    public function hlBlockAction()
    {
        $this->isAjax = true;
        $request = Application::getInstance()->getContext()->getRequest()->toArray();
        $this->request = $request;
        $this->arContragent = UserDataManager\UserDataManager::getContrAgentInfo();

        if (isset($request['ADDRESSES'])) {
            $arAddresses = [];
            foreach ($request['ADDRESSES'] as $address) {
                $arAddresses[] = $address;
            }

            $this->sendMail($arAddresses);
        }

        $this->isSuccess = true;

        return $this->executeComponent();
    }

    /**
     * Реализует жизненный цикл компонента
     */
    public function executeComponent()
    {
        $this->makeResult();

        // AJAX-режим или нет
        if ($this->isAjax === true) {

            ob_start();
            $this->includeComponentTemplate();
            $this->arResult['RESPONSE']['html'] = ob_get_contents();
            ob_end_clean();

        } else {
            $this->includeComponentTemplate();
        }

        return $this->arResult;
    }

    /**
     * @throws Exception
     */
    protected function makeResult()
    {

        $core = Core::getInstance();
        $hlId = $core->getHlBlockId($core::HLBLOCK_CODE_SHIPPING_ADDRESSES);

        $this->arContragent = UserDataManager\UserDataManager::getContrAgentInfo();

        CModule::IncludeModule('highloadblock'); //модуль highload инфоблоков
        $rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('ID'=>$hlId)));
        if ( !($hldata = $rsData->fetch()) ){
            //echo 'Инфоблок не найден';
        }
        else{
            $hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
            $hlDataClass = $hldata['NAME'].'Table';
            $res = $hlDataClass::getList(array(
                    'filter' => array(
                        'UF_KONTRAGENT' => $this->arContragent['UF_XML_ID']
                    ),
                    'select' => array("*"),
                    'order' => array(
                        'ID' => 'asc'
                    ),
                )
            );
            while ($row = $res->fetch()) {
                $HLinfo[] =$row;
            }
        }
        $arResult['UF_SHIPPING_ADDRESSES'] = $HLinfo;

        $this->arResult = $arResult;

        $this->arResult["SUCCESS"] = $this->isSuccess;
        $this->arResult["request"] = $this->request;
        $this->arResult["arAddresses"] = $this->arAddresses;
        $this->arResult["arParams"] = $this->arParams;
    }

    /**
     * Возвращает ключи из $arParams, которые должны участвовать в ajax-запросе к методу компонента
     * @return array|null
     */
    protected function listKeysSignedParameters()
    {
        return [
            'USER_ID',
        ];
    }

    /**
     * Возвращает ключи из $arParams, которые должны участвовать в ajax-запросе к методу компонента
     * @return array|null
     */
    protected function sendMail($arAddresses)
    {

        $arResult['VALUES']["USER_ID"] = $this->arParams['USER_ID'];

        $arEventFields['ADDRESSES'] = $arAddresses;
        $arEventFields['CONTRAGENT_NAME'] = $this->arContragent['UF_NAME'];
        $arEventFields['USER_ID'] = $arResult['VALUES']["USER_ID"];

        $event = new CEvent;
        $event->SendImmediate("NEW_ADDRESSES", SITE_ID, $arEventFields);

        $this->arAddresses = $arAddresses;
    }
}