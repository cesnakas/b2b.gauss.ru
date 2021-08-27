<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;


Loc::loadMessages(__FILE__);


class MailBufferTemplate extends \CBitrixComponent {

    /**
     * @return false|mixed|string
     * @throws Exception
     */
    public function executeComponent()
    {
        if (!$this->arParams['SITE_ID']) {
            $this->arParams['SITE_ID'] = \Citfact\SiteCore\Core::DEFAULT_SITE_ID;
        }

        $SiteList = new \Citfact\DataCache\SiteData\SiteList();
        $this->arParams['SITE'] = $SiteList->getByCode($this->arParams['SITE_ID']);

        $protocol = (CMain::IsHTTPS()) ? "https://" : "http://";
        $this->arParams['FULL_DIR'] = $protocol . $this->arParams['SITE']['SERVER_NAME'];


        $this->arResult = $this->arParams;

        ob_start();
        $this->includeComponentTemplate();
        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }
}