<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Citfact\Sitecore\Manufacturer\HlManufacturerManager;

class FavoritesListComponent extends \CBitrixComponent implements Controllerable
{
    /**
     * @var \Bitrix\Sale\Order $order
     */
    private $result = [];
    private $page = '';
    public $siteId;
    public $isAjax = false;
    private $iblockIdCatalog;
    private $obElement;


    /**
     * @return array
     */
    public function configureActions()
    {
        return [
            /*'productAdd' => [
                'prefilters' => [
                    new ActionFilter\Authentication(),
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ]*/
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        $this->arResult = [];
        $this->siteId = \Bitrix\Main\Context::getCurrent()->getSite();
        $core = \Citfact\SiteCore\Core::getInstance();
        $this->iblockIdCatalog = $core->getIblockId($core::IBLOCK_CODE_CATALOG);

        $arFavorites = \Citfact\Sitecore\Favorites\Favorites::getForUser();

        $this->result['PRODUCTS'] = $arFavorites;
        $this->arResult = $this->result;

        // AJAX-режим или нет
        if ($this->isAjax === true) {
            //ob_start();
            //$this->includeComponentTemplate($this->page);
            //$this->arResult['RESPONSE']['html'] = ob_get_contents();
            //ob_end_clean();
        } else {
            $this->includeComponentTemplate($this->page);
        }

        return $this->arResult;
    }


    /**
     * Возвращает ключи из $arParams, которые должны участвовать в ajax-запросе к методу компонента
     * @return array|null
     */
    protected function listKeysSignedParameters()
    {
        return [
            'PARAM_NAME',
        ];
    }


    /**
     * @param $arParams
     * @return array
     * @throws Exception
     */
    public function onPrepareComponentParams($arParams)
    {
        if (isset($arParams['PERSON_TYPE_ID']) && intval($arParams['PERSON_TYPE_ID']) > 0) {
            $arParams['PERSON_TYPE_ID'] = intval($arParams['PERSON_TYPE_ID']);
        } else {
            if (intval($this->request['person_type_id']) > 0) {
                $arParams['PERSON_TYPE_ID'] = intval($this->request['person_type_id']);
            } else {
                $arParams['PERSON_TYPE_ID'] = 1;
            }
        }

        return $arParams;
    }
}