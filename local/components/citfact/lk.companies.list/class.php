<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Citfact\SiteCore\UserDataManager\UserDataManager;
use Citfact\Tools\Component\BaseComponent;
use Citfact\Tools\ElementManager;
use Citfact\Tools\Tools;
use Bitrix\Main\UserTable;
use Citfact\SiteCore\User\UserRepository;


Loc::loadMessages(__FILE__);


class PortalCompaniesListComponent extends BaseComponent
{
    public static $filterFields = [
        'UF_NAME' => 'Название',
        'UF_INN' => 'ИНН',
    ];

    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        $requestData = Tools::requestSpecialChars($_REQUEST);
        $this->arResult['REQUEST_DATA'] = $requestData;
        $filter = $this->getRequestFilter($requestData);
        $this->arResult['FILTER_FIELDS'] = self::$filterFields;

        $contragents = UserDataManager::getContragentsList($filter);
        $contragents = $this->getRequestNewContragents($contragents);
        $contragents = $this->getCountAcceptContragents($contragents);
        usort($contragents, ['PortalCompaniesListComponent', 'sortByRequestNewContragents']);
        
        $this->arResult['ITEMS'] = $contragents;

        $this->seItemsUrl();

        $this->IncludeComponentTemplate();
    }

    protected function getRequestNewContragents($contragents)
    {

        $filter = [
            ['=UF_TIN' => array_column($contragents, 'UF_INN')],
        ];
        $filter['=UF_ACTIVATE_PROFILE'] = 0;

        $res = UserTable::getList([
            'filter' => $filter,
            'select' => ['*', 'UF_REGIONS', 'UF_CONTRAGENT_IDS', 'UF_ACTIVATE_PROFILE', 'UF_TIN']
        ]);
        $userInn = [];
        while ($user = $res->fetch()) {
            $userInn[$user['UF_TIN']][] = $user['ID'];
        }

        foreach ($contragents as $key => $contragent) {
            if (!empty($userInn[$contragent['UF_INN']])) {
                $contragents[$key]['REQUEST_NEW_CONTRAGENTS'] = count($userInn[$contragent['UF_INN']]);
            }
        }

       return $contragents;
    }

    protected function getCountAcceptContragents($contragents)
    {

        $filter['=UF_ACTIVATE_PROFILE'] = 1;
        $filter['=UF_CONTRAGENT_IDS'] = array_column($contragents, 'ID');

        $res = UserTable::getList([
            'filter' => $filter,
            'select' => ['*', 'UF_REGIONS', 'UF_CONTRAGENT_IDS', 'UF_ACTIVATE_PROFILE', 'UF_TIN']
        ]);

        $contragentId = [];
        $notInGroups = ['MANAGER', 'ASSISTANT', 'ADMINISTRATOR'];

        while ($user = $res->fetch()) {
            if (!UserRepository::checkUserInGroup($user['ID'], $notInGroups)) {
                $uniqueContragentsIds = array_unique($user['UF_CONTRAGENT_IDS']);
                foreach ($uniqueContragentsIds as $contragentsIds) {
                    $contragentId[$contragentsIds][] = $user['EMAIL'];
                }
            }
        }
        foreach ($contragents as $key => $contragent) {
            if (!empty($contragentId[$contragent['ID']])) {
                $contragents[$key]['ACCEPT_CONTRAGENTS'] = count($contragentId[$contragent['ID']]);
            } else {
                $contragents[$key]['ACCEPT_CONTRAGENTS'] = 0;
            }
        }
        return $contragents;
    }

    protected function getRequestFilter($requestData)
    {
        $filter = [];
        if (
            !$requestData['COMPANY_FILTER']['SEARCH_STRING'] ||
            !self::$filterFields[$requestData['COMPANY_FILTER']['SEARCH_TYPE']]
        ) {
            return $filter;
        }
        if ($requestData['COMPANY_FILTER']['SEARCH_TYPE'] == 'MANAGER') {
            $contractorGuid = $this->getContractorGuidByManagerName($requestData['COMPANY_FILTER']['SEARCH_STRING']);
            if ($contractorGuid) {
                $filter['=cash_contractor_guid'] = $contractorGuid;
            } else {
                $filter['=cash_contractor_guid'] = false;
            }
        } else {
            $filter['%=' . $requestData['COMPANY_FILTER']['SEARCH_TYPE']] = '%' . $requestData['COMPANY_FILTER']['SEARCH_STRING'] . '%';
        }
        return $filter;
    }

    protected function seItemsUrl()
    {
        foreach ($this->arResult['ITEMS'] as &$item) {
            $item['URL'] = $this->arParams['SEF_FOLDER'] . $item['UF_XML_ID'] . '/';
        }
        unset($item);
    }

    protected function sortByRequestNewContragents($a, $b)
    {
        if ($a['REQUEST_NEW_CONTRAGENTS'] == $b['REQUEST_NEW_CONTRAGENTS']) {
            return $a['UF_NAME'] < $b['UF_NAME'] ? -1 : 1;
        }
        return $a['REQUEST_NEW_CONTRAGENTS'] < $b['REQUEST_NEW_CONTRAGENTS'] ? 1 : -1;
    }
}

