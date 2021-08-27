<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Iblock\InheritedProperty\SectionValues;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Internals\OrderPropsValueTable;
use Bitrix\Sale\Internals\OrderTable;
use Bitrix\Sale\Order;
use Citfact\SiteCore\Entity\DebitorskayazadolzhennostTable;
use Citfact\Tools\ElementManager;
use Citfact\Sitecore\UserDataManager;
use Bitrix\Highloadblock\HighloadBlockTable;



Loader::includeModule('iblock');
Loc::loadMessages(__FILE__);

class ReceivablesListComponent extends \CBitrixComponent
{
    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        if(!empty($this->arParams['XML_ID'])){
            $contragent = $this->arParams['XML_ID'];
            UserDataManager\UserDataManager::checkContragentXmlIdByStructure($this->arParams['XML_ID']);
        } else {
            $contragent = UserDataManager\UserDataManager::getUserContragentXmlID();
        }

        global $USER;
        Loc::loadMessages(__FILE__);
        if (isset($_GET['PAGEN'])) {
            $currentPage = $_GET['PAGEN'];
        } else
            $currentPage = 0;

        if ($this->StartResultCache(false, $currentPage)) {
            CModule::IncludeModule('highloadblock');

            $this->arResult['arOrder'] = $this->getSortParams();
            $receivables = $this->getReceivablesFromHL($contragent, $currentPage, $this->arResult['arOrder']);
            // общую задолженность
            $allReceivables = $this->getAllReceivablesFromHL($contragent, $currentPage);
            $totalReceivables = 0;
            $overdueReceivables = 0;
            $overdueDays = 0;

            foreach ($allReceivables as $key => $receivable) {
                $totalReceivables += $receivable['UF_SUMMA'];
                $overdueReceivables += $receivable['UF_SUMMAPROSROCHENO'];
                $overdueDays = max($overdueDays, $receivable['UF_DNEYPROSROCHENO']);
            }
            $this->arResult['TOTAL_RECEIVABLES'] = $totalReceivables;
            $this->arResult['OVERDUE_RECEIVABLES'] = $overdueReceivables;
            $this->arResult['OVERDUE_DAYS'] = $overdueDays;

            $now = new DateTime();
            //считаем дни до завершения и просроченные дни оплаты
            foreach ($receivables as $key => $receivable) {
                if (!empty($receivable['UF_DATAOPLATY'])) {
                    $payDate = new DateTime($receivable['UF_DATAOPLATY']);
                    $receivables[$key]['DAYS_TO_PAY'] = (int)$now->diff($payDate)->format("%r%a");
                    if ($receivables[$key]['DAYS_TO_PAY'] < 0) {
                        $receivables[$key]['DAYS_TO_PAY'] = 0;
                    }

                    $receivables[$key]['DAYS_DELAY'] = (int)$payDate->diff($now)->format("%r%a");

                    if ($receivables[$key]['DAYS_DELAY'] < 0) {
                        $receivables[$key]['DAYS_DELAY'] = 0;
                        $receivables[$key]['RED'] = false;
                    } else {
                        $receivables[$key]['RED'] = true;
                    }

                    $receivables[$key]['UF_DATA'] = date('d.m.Y', strtotime($receivable['UF_DATAOPLATY']));
                } else {
                    $receivables[$key]['UF_DATA'] = 'Нет данных';
                    $receivables[$key]['DAYS_TO_PAY'] = 0;
                    $receivables[$key]['DAYS_DELAY'] = 0;
                }
                if (empty($receivable['UF_SUMMAPROSROCHENO'])) {
                    $receivables[$key]['UF_SUMMAPROSROCHENO'] = 0;
                }

                if (!empty($receivable['UF_ZAKAZ'])) {
                    $orderId1CsList[] = $receivable['UF_ZAKAZ'];
                }
            }

            //заполняем данными заказов
            $orders = [];

            if (!empty($orderId1CsList)) {
                $orders = $this->getOrders($orderId1CsList);
            }

            foreach ($receivables as $key => $receivable) {
                $orderID = $receivable['UF_ZAKAZ'];
                if (isset($orders[$orderID])) {
                    $receivables[$key]['UF_NOMER'] = $orders[$orderID]['ID'];
                    $receivables[$key]['ORDER_CREATE_DATE'] = date("d.m.Y", strtotime($orders[$orderID]['DATE_INSERT']));
                    $receivables[$key]['ORDER_PRICE'] = $orders[$orderID]['PRICE'];
                    $receivables[$key]['ORDER_ITEMS_COUNT'] = $orders[$orderID]['ITEMS_COUNT'];
                    $receivables[$key]['ORDER_USER_NAME'] = $orders[$orderID]['FIO'] ?: 'Нет данных';
                    $receivables[$key]['OFFLINE'] = $orders[$orderID]['OFFLINE'] ?: false;
                } else {
                    $receivables[$key]['UF_NOMER'] = $receivable['UF_NOMER'] ?: 'Нет данных';
                    $receivables[$key]['ORDER_CREATE_DATE'] = $receivable['UF_DATA'] ?: 'Нет данных';
                    $receivables[$key]['ORDER_PRICE'] = 0;
                    $receivables[$key]['ORDER_ITEMS_COUNT'] = 0;
                    $receivables[$key]['ORDER_USER_NAME'] = 'Нет данных';
                    $receivables[$key]['OFFLINE'] = false;
                }
            }

            $this->arResult['RECEIVABLES'] = $receivables;
            $this->arResult['CURRENT_PAGE'] = $currentPage;
            $allReceivablesCount = $this->getReceivablesCount($contragent);
            $pageElementsCount = (int)$this->arParams['ELEMENTS_COUNT'];
            $lastPage = ceil($allReceivablesCount / $pageElementsCount) - 1;
            $this->arResult['CURRENT_PAGE'] = $currentPage;
            $this->arResult['LAST_PAGE'] = $lastPage;
            $this->IncludeComponentTemplate();
        }
    }

    private function getSortParams()
    {
        $result = [];
        if (isset($_GET['sort']) && !empty($_GET['sort'])) {
            $params = explode('|', $_GET['sort']);
            $result[$params[0]] = $params[1];
        } else {
            $result = ['UF_SUMMAPROSROCHENO' => 'desc'];
        }

        return $result;
    }

    public function getSortParamsForSelect()
    {
        return [
            [
                'name' => 'UF_SUMMAPROSROCHENO',
                'sort' => 'desc',
                'label' => 'Просроченная задолженность &#8595;(По убыванию)'
            ],
            [
                'name' => 'UF_DATAOPLATY',
                'sort' => 'asc',
                'label' => 'Количество дней просрочки &#8595;(По убыванию)'
            ],
            [
                'name' => 'UF_TIME',
                'sort' => 'desc',
                'label' => 'Дата заказа &#8595;(По убыванию)'
            ],
            [
                'name' => 'UF_TIME',
                'sort' => 'asc',
                'label' => 'Дата заказа &#8593;(По возрастанию)'
            ],
        ];
    }

    /**
     * @param $arParams
     * @return array
     * @throws Exception
     */
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    /**
     * @param $contragent
     * @param $currentPage
     * @return array
     */
    private function getReceivablesFromHL($contragent, $currentPage, $arOrder = [])
    {
        $core = \Citfact\SiteCore\Core::getInstance();
        $hl_id = $core->getHlBlockId('DebitorskayaZadolzhennost');
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $limit = (int)$this->arParams['ELEMENTS_COUNT'];

        $rsData = DebitorskayazadolzhennostTable::getList(array(
            'select' => array('*'),
            'order' => $arOrder,
            'filter' => ['UF_KONTRAGENT' => $contragent],
            "count_total" => true,
            "offset" => $currentPage * $limit,
            "limit" => $limit,
        ));
        $receivables = [];
        while ($el = $rsData->fetch()) {
            $receivables[] = $el;
        }
        return $receivables;
    }

    private function getAllReceivablesFromHL($contragent, $currentPage)
    {
        $core = \Citfact\SiteCore\Core::getInstance();
        $hl_id = $core->getHlBlockId('DebitorskayaZadolzhennost');
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $limit = (int)$this->arParams['ELEMENTS_COUNT'];
        $rsData = $entity_data_class::getList(array(
            'select' => array('*'),
            'filter' => ['UF_KONTRAGENT' => $contragent],
        ));
        $receivables = [];
        while ($el = $rsData->fetch()) {
            $receivables[] = $el;
        }
        return $receivables;
    }

    private function getReceivablesCount($contragent)
    {
        $core = \Citfact\SiteCore\Core::getInstance();
        $hl_id = $core->getHlBlockId('DebitorskayaZadolzhennost');
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            'select' => array('*'),
            'filter' => ['UF_KONTRAGENT' => $contragent],
        ));
        $receivablesCount = 0;
        while ($el = $rsData->fetch()) {
            $receivablesCount++;
        }
        return $receivablesCount;
    }

    private function getOrders($orderId1CsList)
    {
        $orders = [];
        $orderIdsList = [];
        $usersIds = [];

        $db_orders = OrderTable::getList(
            [
                'select' => ['*'],
                'filter' => ['ID_1C' => $orderId1CsList]
            ]
        );

        while ($order = $db_orders->fetch()) {
            $orderIdsList[$order['ID_1C']] = $order['ID'];
            $orders[$order['ID_1C']] = $order;

            if (!empty($order['USER_ID'])) {
                $usersIds[$order['USER_ID']] = $order['USER_ID'];
            }

        }

        if (!empty($usersIds)) {
            $by = 'id';
            $orderRow = 'desc';
            $usersDb = CUser::GetList($by, $orderRow, ['ID' => $usersIds], ['NAME', 'SECOND_NAME', 'LAST_NAME']);

            while ($user = $usersDb->Fetch()) {
                $usersIds[$user['ID']] = trim($user['LAST_NAME'] . ' ' . $user['NAME'] . ' ' . $user['SECOND_NAME']);
            }

            foreach ($orders as $orderId => $order) {
                if (!empty($order['USER_ID'])) {
                    $orders[$orderId]['FIO'] = $usersIds[$order['USER_ID']];
                }
            }
        }

        $offlineOrdersDB = OrderPropsValueTable::getList([
            'select' => ['CODE', 'ORDER_ID', 'VALUE'],
            'filter' => ['CODE' => 'OFFLINE', '!=VALUE' => null, 'ORDER_ID' => $orderIdsList]
        ]);

        while ($orderDB = $offlineOrdersDB->fetch()) {
            $orderId1c = array_search($orderDB['ORDER_ID'], $orderIdsList);

            if (false !== $orderId1c) {
                $orders[$orderId1c]['OFFLINE'] = true;
            }
        }

        $amountItemsOfOrder = $this->getAmountOfItemsInOrder($orderIdsList);

        foreach ($amountItemsOfOrder as $orderId => $amount) {
            $orders[array_search($orderId, $orderIdsList)]['ITEMS_COUNT'] = $amount;
        }

        return $orders;
    }

    private function getAmountOfItemsInOrder($orderIdList)
    {
        $amountItemsOfOrder = [];
        $arFilter = ["ORDER_ID" => $orderIdList];
        $db_orders = CSaleBasket::GetList(false, $arFilter);
        while ($item = $db_orders->Fetch()) {
            $amountItemsOfOrder[$item['ORDER_ID']][] = $item['ID'];
        }

        if (!empty($amountItemsOfOrder)) {
            foreach ($amountItemsOfOrder as $orderId => $items) {
                $amountItemsOfOrder[$orderId] = count($items);
            }
        }

        return $amountItemsOfOrder;

    }
}