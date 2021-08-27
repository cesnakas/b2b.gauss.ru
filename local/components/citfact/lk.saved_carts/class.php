<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Citfact\Sitecore\CatalogHelper\ElementRepository;
use Citfact\Sitecore\Notification\NotificationManager;
use Citfact\SiteCore\OrderTemplate\OrderTemplateManager;
use Citfact\SiteCore\Rezervy\RezervyManager;

class LkSavedCartsComponent extends CBitrixComponent implements Controllerable
{

    /**
     * Массив со списком ошибок, полученных в процессе работы компонента
     * @var array of errors.
     */
    protected $errors = [];

    /**
     * Кол-во элементов на страницу
     * @var string
     */
    protected $itemsCount;

    /**
     * @var Citfact\SiteCore\OrderTemplate\OrderTemplateManager
     */
    protected $orderTplManager;

    /**
     * Был ли вызов ajax запросом
     * @var bool
     */
    public $isAjax = false;

    /**
     * Был ли поиск
     * @var bool
     */
    protected $isSearch = false;

    /**
     * Успешное выполнение обработки данных после ajax запроса
     * @var bool
     */
    protected $isSuccess = false;

    /**
     * LkOrderTemplatesComponent constructor.
     * @param CBitrixComponent|null $component
     */
    public function __construct(CBitrixComponent $component = null)
    {
        parent::__construct($component);

        $this->orderTplManager = new OrderTemplateManager();

        CPageOption::SetOptionString("main", "nav_page_in_session", "N");

        Loc::loadMessages(__FILE__);
    }

    /**
     * @return array
     */
    public function configureActions()
    {
        return [
            'templateChange' => [
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
            ],
            'templateRename' => [
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
            ],
            'templateCreateReminder' => [
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
            ],
        ];
    }


    /**
     * Изменить способ уведомлений
     *
     * @param $newVal
     * @return array
     */
    public function changeRemindersTypeAction($newVal)
    {
        $this->isAjax = true;
        $this->isSuccess = false;
        $result = "";
        try {
            global $USER;
            $notificationManager = new NotificationManager();
            $result = $notificationManager->setOrderTemplatesReminderMethod($USER->GetID(), $newVal);
        } catch (Exception $e) {
            $this->errors[$e->getCode()] = $e->getMessage();
        }

        if (empty($this->errors)) {
            $this->isSuccess = true;
        }

        return [
            "SUCCESS" => $this->isSuccess,
            "MSG" => $result,
            "ERRORS" => $this->errors,
            "DEBUG" => ['userId' => $USER->GetID(), 'newVal' => $newVal],
        ];
    }


    /**
     * Создать напоминание. Метод вызывается AJAX запросом
     *
     * @return array
     * @throws Main\SystemException
     */
    public function templateCreateReminderAction()
    {
        $this->isAjax = true;
        $this->isSuccess = false;
        $request = Application::getInstance()->getContext()->getRequest()->toArray();
        $html = "";

        try {
            $tplId = (int)trim(strip_tags($request['tpl_id']));
            $newReminder = trim(strip_tags($request['create_reminder']));

            if (!$tplId) {
                $this->errors[] = Loc::getMessage('LK_TEMPLATES_TPL_ID_ERROR');
            }
            if (!strtotime($newReminder)) {
                $this->errors[] = Loc::getMessage('LK_TEMPLATES_NEW_REMINDER_ERROR');
            }

            $arReminder = $this->orderTplManager->getReminders($tplId);
            if (count($arReminder) >= 10) {
                $this->errors[] = Loc::getMessage('LK_TEMPLATES_REMINDER_COUNT_ERROR');
            }

            if (empty($this->errors)) {

                if (!($resultTimeStamp = $this->orderTplManager->addReminder($tplId, $newReminder))) {
                    throw new Exception(Loc::getMessage('LK_TEMPLATES_NEW_REMINDER_ALREADY_EXISTS_ERROR'));
                }

                $html = '
                    <div class="notify-label"
                        data-action-id="' . $resultTimeStamp . '"
                        data-action
                        data-action-type="delete_reminder">
                        ' . $newReminder . '
                        <svg class="i-icon">
                            <use xlink:href="#icon-cross"/>
                        </svg>
                    </div>
                ';

            }
        } catch (Exception $e) {
            $this->errors[$e->getCode()] = $e->getMessage();
        }

        if (empty($this->errors)) {
            $this->isSuccess = true;
        }

        return [
            "SUCCESS" => $this->isSuccess,
            "HTML" => $html,
            "ERRORS" => $this->errors
        ];

    }

    /**
     * Переименовать шаблон. Метод вызывается AJAX запросом
     *
     * @return array
     * @throws Main\SystemException
     */


    /**
     * @throws Exception
     */
    protected function makeResult()
    {
        // Собрать данные о шаблонах и товарах в них
        $arItems = [];
        $arTemplates = $this->orderTplManager->getTemplates();

        foreach ($arTemplates as $tplId => $arTemplate) {
            // Сформировать массив для передачи в шаблон
            $arItems[$tplId] = [
                "ID" => $tplId,
                "NAME" => $arTemplate['UF_NAME'],
                "DESCRIPTION" => $arTemplate['UF_DESCRIPTION'],
                "CREATE_DATE" => $arTemplate['UF_TIMESTAMP']->format("d.m.Y"),
                "IS_REMINDER" => !empty($arTemplate['UF_DATE_NOTIFY']),
                "CONTRAGENT" => $arTemplate['UF_CONTRAGENT'],
                "PRODUCTS" => $this->orderTplManager->getProductsData($tplId) // Получить массив с данными о товарах
            ];
        }

        // make CDBResult object from array for create pagination
        $cdbResultObj = new CDBResult;
        $cdbResultObj->InitFromArray($arItems);
        $this->arResult['CDBRESULT_OBJ'] = $cdbResultObj;

        while ($arElement = $cdbResultObj->Fetch()) {
            $this->arResult['ITEMS'][] = $arElement;
        }

        //считаем общую стоимость корзины
        foreach ($this->arResult['ITEMS'] as $key => $savedCart) {
            $savedCartTotalPrice = 0;
            foreach ($savedCart['PRODUCTS'] as $product) {
                $product['SUM'] = str_replace(',', '.', $product['SUM']);
                $savedCartTotalPrice += filter_var($product['SUM'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            }
            $this->arResult['ITEMS'][$key]['ORIGIN_TOTAL_PRICE'] = $savedCartTotalPrice;
            $this->arResult['ITEMS'][$key]['TOTAL_PRICE'] = ElementRepository::formatFullPrice(
                $savedCartTotalPrice,
                "RUB"
            );
        }

        $arXMLID = [];
        foreach ($this->arResult['ITEMS'] as $savedCart) {
            foreach ($savedCart['PRODUCTS'] as $product) {
                $arXMLID[] = $product['XML_ID'];
            }
        }

        $this->arResult['RESERV_BALANCE'] = [];
        $RESERV_BALANCE = Citfact\SiteCore\Rezervy\RezervyManager::getListByNomenclaturers($arXMLID);

        foreach ($RESERV_BALANCE as $balance) {
            $this->arResult['RESERV_BALANCE'][$balance['UF_NOMENKLATURA']] = $balance;
        }

        foreach ($this->arResult['ITEMS'] as &$savedCart) {
            foreach ($savedCart['PRODUCTS'] as &$product) {
                $nomenclature = $product['XML_ID'];
                $product['RESERV_BALANCE'] = $this->arResult['RESERV_BALANCE'][$nomenclature];

                if (empty($product['NAME'])) {
                    $savedCart['GHOST_PRODUCTS'][] = $product['PRODUCT_ID'];
                }

                unset($product);
            }
            unset($savedCart);
        }

        $this->arResult["SUCCESS"] = $this->isSuccess;
    }


    /**
     * Реализует жизненный цикл компонента
     */
    public function executeComponent()
    {
        try {
            $this->setFrameMode(false);
            $this->makeResult();
        } catch (Exception $e) {

            $this->errors[$e->getCode()] = $e->getMessage();
        }

        $this->formatResultErrors();

        $this->includeComponentTemplate();
        return $this->arResult;
    }

    /**
     * Добавляет все ошибки в $arResult
     * @return void
     */
    protected function formatResultErrors()
    {
        if (!empty($this->errors)) {
            $this->arResult['ERRORS'] = $this->errors;
        }
    }
}
