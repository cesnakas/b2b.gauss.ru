<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\Core;

Loc::loadMessages(__FILE__);

class PortalCompaniesComponent extends \CBitrixComponent
{
    private static $requiredParams = array(
        'SEF_FOLDER',
    );

    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        global $APPLICATION;
        $dir = $APPLICATION->GetCurDir();

        $this->validateParams();
        $this->validatePath($dir);

        $dir = str_replace($this->arParams['SEF_FOLDER'], '', $dir);
        $dirExplode = explode('/', $dir);
        $elementId = $dirExplode[0];
        $dirPageCode = $dirExplode[1];

        $page = 'list';
        if (empty($elementId)) {
            $page = 'list';
        } else {
            $element = $this->getCompany($elementId);

            if ($element) {
                $this->arResult['ELEMENT_ID'] = $element['cash_contractor_guid'];
                $this->arResult['ELEMENT_NAME'] = $element['cash_contractor_name'];
                $APPLICATION->AddChainItem(
                    $this->arResult['ELEMENT_NAME'],
                    $this->arParams['SEF_FOLDER'] . $this->arResult['ELEMENT_ID'] . '/'
                );
                $page = 'detail';
                if ($dirPageCode == 'history') {
                    $page = 'history';
                    $APPLICATION->AddChainItem('История запросов');
                }
            } else {
                Tools::process404('MESSAGE_404', true, true, true);
            }
        }
        $this->IncludeComponentTemplate($page);
    }

    private function getCompany($elementId)
    {
        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_KONTRAGENTY);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            'select' => array('*'),
            'filter' => ['UF_XML_ID' => $elementId],
        ));

        $el = $rsData->fetch();
        $company = [
            'cash_contractor_guid' => $el['UF_XML_ID'],
            'cash_contractor_name' => $el['UF_NAME'],

        ];
        return $company;
    }

    private function validatePath($dir)
    {
        $dirExplode = explode('/', str_replace($this->arParams['SEF_FOLDER'], '', $dir));
        $sefFolderStrPos = strpos($dir, $this->arParams['SEF_FOLDER']);
        if (
            (
                count($dirExplode) > 2 &&
                $dirExplode[1] !== 'history'
            ) ||
            $sefFolderStrPos === false ||
            $sefFolderStrPos != 0
        ) {
            Tools::process404('MESSAGE_404', true, true, true);
        }
    }

    private function validateParams()
    {
        foreach (self::$requiredParams as $code) {
            if (!$this->arParams[$code]) {
                throw new Exception('Wrong ' . $code . ' param.');
            }
        }
    }
}


