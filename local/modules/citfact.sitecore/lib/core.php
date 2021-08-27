<?php

namespace Citfact\SiteCore;

use Citfact\Tools\HLBlock;

class Core
{
    const DOMAIN = 'https://b2b.gauss.ru';

    const CONSTANTS_FILE_PATH = '/local/php_interface/constants.dat';
    const NO_PHOTO_SRC = '/local/client/img/no-photo.jpg';
    const DEFAULT_SITE_ID = 's1';
    const DEFAULT_CURRENCY = 'RUB';

    const IMAGE_PLACEHOLDER = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/P3PUwAJmAPNM95PkgAAAABJRU5ErkJggg==';
    const IMAGE_PLACEHOLDER_TRANSPARENT = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
    const IMAGE_PLACEHOLDER_BLACK= 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkqAcAAIUAgUW0RjgAAAAASUVORK5CYII=';

    const IBLOCK_CODE_CATALOG = 'main_catalog';
    const IBLOCK_CODE_ACTIONS = 'actions';
    const IBLOCK_CODE_MENU_LEFT = 'menu_left';
    const IBLOCK_CODE_MENU_ACCOUNT = 'menu_account';
    const IBLOCK_CODE_PROJECTS = 'projects';
    const IBLOCK_CODE_FOOTER_SOC_LINKS = 'footer_soc_links';
    const IBLOCK_CODE_PROMOTIONS = 'promotions';
    const IBLOCK_CODE_EDUCATIONAL_PRESENTATION = 'educational_presentation';
    const IBLOCK_CODE_PRESENTATIONS_TESTS = ' presentations_in_tests';
    const IBLOCK_CODE_EDUCATIONAL_TESTS = 'educational_tests';
    const IBLOCK_CODE_EDUCATIONAL_VIDEOS = 'educational_video';
    const IBLOCK_CODE_EDUCATIONAL_TRAIN_AN_EMPLOYEE = 'educational_train_an_employee';
    const IBLOCK_CODE_PRESS_CENTER_NEWS = 'news';
    const IBLOCK_CODE_PRESS_CENTER_ARTICLES = 'articles';
    const IBLOCK_CODE_PRESS_CENTER_LEAFLETS = 'leaflets';
    const IBLOCK_CODE_PRESS_CENTER_PHOTO_AND_VIDEO = 'photo_and_video';
    const IBLOCK_CODE_ABOUT_COMPANY_MAIN = 'about_company_main';
    const IBLOCK_CODE_ABOUT_COMPANY_SECTION = 'about_company_section';
    const IBLOCK_CODE_DELIVERY_PAYMENT = 'payment';
    const IBLOCK_CODE_DELIVERY_PICKUP = 'pickup';
    const IBLOCK_CODE_DELIVERY_ROUTE = 'route';
    const IBLOCK_CODE_PRIVACY_POLOCY = 'privacy_policy';
    const IBLOCK_CODE_MARKETING_SUPPORT_TRADING_EQUIPMENT_POS_MATERIALS = 'trading_equipment_pos_materials_new';
    const IBLOCK_CODE_MARKETING_SUPPORT_CUSTOMIZED_SOLUTIONS = 'customized_solutions_new';
    const IBLOCK_CODE_MARKETING_SUPPORT_CATALOGS_FLYERS = 'catalogs_flyers';
    const IBLOCK_CODE_MARKETING_SUPPORT_SOUVENIRS = 'souvenirs_new';
    const IBLOCK_CODE_MARKETING_SUPPORT_PROMOTIONAL_MATERIALS = 'promotional_materials_new';
    const IBLOCK_CODE_UPAKOVKA_MEASURE_ID = 7;
    const IBLOCK_CODE_B2B_BANNER_ID = 'b2b_banner';

    const HLBLOCK_CODE_PICKUP = 'PickupAddresses';
    const HLBLOCK_CODE_SHIPPING_ADDRESSES = 'AdresaDostavki';
    const HLBLOCK_CODE_TIPY_TSEN_KONTRAGENTOV = 'TipyTSenKontragentov';
    const HLBLOCK_CODE_KONTRAGENTY = 'Kontragenty';
    const HLBLOCK_CODE_ORDER_TEMPLATES = 'OrderTemplate';
    const HLBLOCK_CODE_REZERVY = 'Rezervy';
    const HLBLOCK_CODE_DOCUMENTATION = 'Dokumentatsiya';
    const HLBLOCK_CODE_REKOMENDOVANNYE_TOVARY = 'RekomendovannyeTovary';
    const HLBLOCK_CODE_STATUSY_TOVAROV = 'StatusyTovarov';
    const HLBLOCK_CODE_PLAN_FACT = 'FaktOtchet';
    const HLBLOCK_CODE_PLAN_FACT_MANAGER = 'PlanFactManager';
    const HLBLOCK_CODE_PLAN_FACT_GENERAL = 'PlanFactGeneral'; 
    const HLBLOCK_CODE_OTGRUZKI = 'Otgruzki';
    const HLBLOCK_CODE_ORDER_FILES = 'SvyazannyeFayly';
    const HLBLOCK_CODE_ORDER_REGIONS = 'Regiony';
    const HLBLOCK_CODE_DEBITORSKAYA_ZADOLZHENNOST = 'DebitorskayaZadolzhennost';
    const HLBLOCK_CODE_MANAGERS = 'Menedzhery';
    const HLBLOCK_CODE_ASSISTANTS = 'Assistenty';
    const HL_BLOCK_CODE_REVIEWS = "Reviews";
    const HLBLOCK_CODE_DEPARTMENT = 'Department';
    const HLBLOCK_CODE_LEVEL_MANAGER = 'LevelManager';
    const HLBLOCK_CODE_STRUCTURE_MANAGERS = 'StructureManagers';
    const HL_BLOCK_CODE_LIST_WAIT = "ListWait";
    const HL_BLOCK_CODE_INTERNET_RESOURCES = 'InternetResources';
    const HL_BLOCK_CODE_INTERNET_NOMENCLATURE = 'InternetResourcesNomenclature';

    const FTP_SERVER_HOST = 'ftp.vartongroup.ru';
    const FTP_SERVER_LOGIN = 'manager';
    const FTP_SERVER_PASSWORD = '7HEkJ7g';

    const IBLOCK_SECTION_CODES_PROMO = ['rasprodazha-gauss', 'aktsiya-gauss', 'rasprodazha_gauss', 'aktsiya_gauss'];
    const IBLOCK_SECTION_ID_PROMO = 1058;

    const IBLOCK_SECTION_CODE_SMART_LAMPS = 'gauss-umnyy-svet';

    const USER_GROUP_MANAGER = 'MANAGER';
    const USER_GROUP_ASSISTANT = 'ASSISTANT';
    const USER_GROUP_ID_CONTENT = 8;

    const ORDER_PROPERTY_ID_OFFLINE = 22;

    const WEB_FORM_ID = [11, 12, 13];
    const SHORTAGE_WEB_FORM_SID = 'SIMPLE_FORM_21';
    const SHORTAGE_INVOICE_FIELD_SID = 'INVOICE_NUMBER';


    private $curDir;
    private $curPage;
    private $constants;

    /**
     * @var Core The reference to *Singleton* instance of this class
     */
    protected static $instance;

    /**
     * Returns the *Core* instance of this class.
     *
     * @return Core The *Core* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param $iblockCode
     * @return string
     * @throws \Exception
     */
    public function getIblockId($iblockCode)
    {
        if (!$iblockCode) {
            throw new \Exception('Empty iblock code.');
        }
        if ($this->constants['IBLOCK_' . $iblockCode]) {
            return $this->constants['IBLOCK_' . $iblockCode];
        }

        $iblock = new \CIBlock();
        $res = $iblock->GetList([], ['CODE' => $iblockCode]);
        $item = $res->Fetch();
        if (!$item['ID']) {
            throw new \Exception('Iblock with code ' . $iblockCode . ' not found.');
        }
        $this->constants['IBLOCK_' . $iblockCode] = $item['ID'];
        ksort($this->constants);

        file_put_contents(
            $_SERVER['DOCUMENT_ROOT'] . self::CONSTANTS_FILE_PATH,
            json_encode($this->constants)
        );

        return $item['ID'];
    }

    /**
     * @param $iblockCode
     * @return string
     * @throws \Exception
     */
    public function getIblockSectionId($iblockCode, $sectionCode)
    {
        $iblockId = $this->getIblockId($iblockCode);

        $obSect = new \CIBlockSection();

        $res = $obSect->GetList(array(), array('IBLOCK_ID' => $iblockId, 'CODE' => $sectionCode));
        $section = $res->Fetch();

        return $section["ID"];
    }


    /**
     * @param $formSID
     * @return string
     * @throws \Exception
     */
    public function  getFormIdBySid($formSID)
    {
        $rsForm = \CForm::GetBySID($formSID);
        $arForm = $rsForm->Fetch();
        return  $arForm['ID'];
    }

    /**
     * @param $fieldSID
     * @return string
     * @throws \Exception
     */
    public function  getfieldIdBySid($fieldSID)
    {
        $rsField = \CFormField::GetBySID($fieldSID);
        $arField = $rsField->Fetch();
        return  $arField['ID'];
    }


    /**
     * @param $hlBlockCode
     * @return string
     * @throws \Exception
     */
    public function getHlBlockId($hlBlockCode)
    {
        if (!$hlBlockCode) {
            throw new \Exception('Empty hlBlock code.');
        }
        if ($this->constants['HL_BLOCK_' . $hlBlockCode]) {
            return $this->constants['HL_BLOCK_' . $hlBlockCode];
        }

        $hlBlock = new HLBlock();
        $hlData = $hlBlock->getHlDataByName($hlBlockCode);

        if (!$hlData['ID']) {
            throw new \Exception('HlBlock with code ' . $hlBlockCode . ' not found.');
        }
        $this->constants['HL_BLOCK_' . $hlBlockCode] = $hlData['ID'];
        ksort($this->constants);

        file_put_contents(
            $_SERVER['DOCUMENT_ROOT'] . self::CONSTANTS_FILE_PATH,
            json_encode($this->constants)
        );

        return $hlData['ID'];
    }

    /**
     * @param $groupCode
     * @return array
     * @throws \Exception
     */
    function GetGroupByCode($groupCode)
    {
        $cGroup = new \CGroup();

        $rsGroups = $cGroup->GetList($by = "c_sort", $order = "asc", array("STRING_ID"=>$groupCode));
        if(intval($rsGroups->SelectedRowsCount()) > 0)
        {
            while($arGroups = $rsGroups->Fetch())
            {
                $arUsersGroups[] = $arGroups['ID'];
            }
        }

        return $arUsersGroups;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Core* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
        global $APPLICATION;
        $this->curDir = $APPLICATION->GetCurDir();
        $this->curPage = $APPLICATION->GetCurPage();

        $fileData = file_get_contents($_SERVER['DOCUMENT_ROOT'] . self::CONSTANTS_FILE_PATH);
        $this->constants = json_decode($fileData, true);
    }


    /**
     * @return string
     */
    public function getCurDir()
    {
        return $this->curDir;
    }


    /**
     * @return string
     */
    public function getCurPage()
    {
        return $this->curPage;
    }


    /**
     * Private clone method to prevent cloning of the instance of the
     * *Core* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }


    /**
     * Private unserialize method to prevent unserializing of the *Core*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }


    public function getTest($str)
    {
        echo $str;
    }
}
