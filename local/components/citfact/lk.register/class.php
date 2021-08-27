<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Citfact\Tools\ElementManager;
use Citfact\Tools\Tools;
use Citfact\Tools\User\UserFieldEnumTable;

Loc::loadMessages(__FILE__);

class PortalRegisterComponent extends \CBitrixComponent
{
    private static $legalUserFieldCodes = [
        'UF_DIRECTOR_NAME',
        'UF_IIN',
        'UF_INN',
        'UF_KPP',
        'UF_COMPANY_NAME',
        'UF_LEGAL_FORM',
        'UF_OTHER_LEGAL_FORM',
        'UF_ADDRESS_LEGAL',
        'UF_ADDRESS_FACT',
        'UF_COMPANY_PHONE',
        'UF_WORK',
        'UF_OTHER_WORK',
        'UF_REG_CLIENT_TYPE',
        'UF_CITY_NAME',
    ];

    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        $app = Application::getInstance();
        $requestData = $app->getContext()->getRequest()->getPostList()->toArray();
        $requestData = $this->formatPostValues($requestData);
        $this->arResult['REQUEST_DATA'] = Tools::requestSpecialChars($requestData);
        $this->arResult['IP_LEGAL_FORM_ID'] = $this->getIpLegalFormId();
        if ($requestData[$this->arParams['FORM_CODE']] == 'Y') {
            $this->register($this->arResult['REQUEST_DATA']);
        }
        if ($this->arResult['SUCCESS']) {
            $this->arResult['REQUEST_DATA'] = [];
        }

        $this->IncludeComponentTemplate();
    }

    private function getIpLegalFormId()
    {
        foreach ($this->arResult['ENUM']['UF_LEGAL_FORM'] as $item) {
            if ($item['XML_ID'] == 'ip') {
                return $item['ID'];
            }
        }
        return '';
    }

    private function getEnumValues()
    {
        $result = [];
        $userFieldEnum = new UserFieldEnumTable();
        $res = $userFieldEnum->getList([
            'filter' => [
                '=USER_FIELD.FIELD_NAME' => self::$legalUserFieldCodes,
                '=USER_FIELD.ENTITY_ID' => 'USER',
                '=USER_FIELD.USER_TYPE_ID' => 'enumeration',
            ],
            'select' => [
                'VALUE',
                'XML_ID',
                'ID',
                'USER_FIELD_NAME' => 'USER_FIELD.FIELD_NAME',
            ],
            'order' => [
                'SORT' => 'ASC',
            ],
        ]);
        while ($item = $res->fetch()) {
            $result[$item['USER_FIELD_NAME']][$item['ID']] = $item;
        }
        return $result;
    }

    private function formatPostValues($requestData)
    {
        if (!$requestData) {
            $requestData['UF_NOTIFICATION'] = 1;
            $requestData['UF_AGREEMENT'] = 1;
        }
        $requestData['PHONE'] = Tools::formatPhone($requestData['PHONE'], false);
        return $requestData;
    }

    private function register($requestData)
    {
        $localization = new Localization();
        $innName = $localization->getInnName();
        $catalogHelperOrder = new Order();
        $portalGroup = new PortalGroup();
        $user = new \CUser();
        $userRepository = new UserRepository();
        $userData = $userRepository->findOneWhere(['EMAIL' => $requestData['EMAIL']], ['ID']);
        $requestData['UF_INN'] = str_replace('_', '', $requestData['UF_INN']);
        if (
            $requestData['UF_INN'] &&
            strlen($requestData['UF_INN']) != 10 &&
            strlen($requestData['UF_INN']) != 12
        ) {
            $this->arResult['ERROR'] = 'Неверный формат ' . $innName . '.';
            return;
        }
        if (
            $requestData['UF_IIN'] &&
            strlen($requestData['UF_IIN']) != 12
        ) {
            $this->arResult['ERROR'] = 'Неверный формат ИИН.';
            return;
        }
        if (
            $requestData['UF_KPP'] &&
            strlen($requestData['UF_KPP']) != 9
        ) {
            $this->arResult['ERROR'] = 'Неверный формат КПП.';
            return;
        }
        if ($userData) {
            $this->arResult['ERROR'] = 'Пользователь с таким E-mail уже зарегистрирован.';
            return;
        }
        $groupCode = ($this->arParams['FORM_CODE'] == 'REGISTER_UL')
            ? PortalGroup::GROUP_UL_MINUS
            : PortalGroup::GROUP_FL_PLUS;

        $fields = array(
            'LOGIN' => $requestData['EMAIL'],
            'EMAIL' => $requestData['EMAIL'],
            'NAME' => $requestData['NAME'],
            'PERSONAL_PHONE' => $requestData['PHONE'],
            'ACTIVE' => ($this->arResult['IS_AUL']) ? 'Y' : 'N',
            'PASSWORD' => $requestData['PASSWORD'],
            'CONFIRM_PASSWORD' => $requestData['CONFIRM_PASSWORD'],
            'UF_NOTIFICATION' => ($requestData['UF_NOTIFICATION']) ? 1 : 0,
            'UF_AGREEMENT' => ($requestData['UF_AGREEMENT']) ? 1 : 0,
            'GROUP_ID' => $portalGroup->getGroupIdsByCodes($groupCode),
            'UF_IS_LEGAL_PERSON' => ($this->arParams['FORM_CODE'] == 'REGISTER_UL') ? 'Y' : '',
            'UF_FILE' => $_FILES['UF_FILE'],
        );

        if ($requestData['ACCESS_LEVEL'] == 1) {
            $fields['GROUP_ID'] = $portalGroup->getGroupIdsByCodes(PortalGroup::GROUP_UL_PLUS);
        }
        if ($this->arResult['IS_MANAGER'] || $this->arResult['IS_AUL']) {
            $fields['UF_AGREEMENT'] = 0;
        }

        $userFields = [];
        foreach (self::$legalUserFieldCodes as $code) {
            $userFields[$code] = $requestData[$code];
        }
        $fields = array_merge($fields, $userFields);

        $userId = $user->Add($fields);
        if (!$userId) {
            $this->arResult['ERROR'] = $user->LAST_ERROR;
            return;
        }

        $outputNumber = $catalogHelperOrder->getCustomId($userId, 'РЕГИС-');
        $this->arResult['SUCCESS'] = ($this->arResult['IS_MANAGER'] || $this->arResult['IS_AUL'])
            ? 'Заявка на регистрацию на клиентском портале № ' . $outputNumber . ' принята.<script>window["Analytics"].yaMetrikaGoal("kp_add_new_user_form_btn");</script>'
            : 'Ваша заявка на регистрацию на клиентском портале № ' . $outputNumber . ' принята.<br> В ближайшее время с вами свяжется сотрудник клиентского сервиса.<script>window["Analytics"].registerFormSuccess();window["Analytics"].yaMetrikaGoal("kp_register");</script>';

        $this->sendMail($outputNumber, $requestData, $userFields, $userId);
        $this->addHistoryItem($userId);
        $this->sendRest($outputNumber, $requestData);
    }

    protected function sendRest($outputNumber, $requestData)
    {
        $elementManager = new ElementManager();
        $webServiceQueue = new WebServiceQueue();
        $contractorRepository = new ContractorRepository();
        $portalGroup = new PortalGroup();
        if (!$portalGroup->isInGroup(PortalGroup::GROUP_AUL)) {
            return;
        }

        $contractorData = $contractorRepository->getContractorDataWithRelations([
            $contractorRepository->getPrimaryKey() => $this->arParams['CONTRACTOR_GUID']
        ]);

        $fields = [
            'organization_guid' => $contractorData['data_cash_guid_org'],
            'servicecode' => $outputNumber,
            'contractor_guid' => $this->arParams['CONTRACTOR_GUID'],
            'fio' => $requestData['NAME'],
            'email' => $requestData['EMAIL'],
            'phone' => $elementManager->stylePhone($requestData['PHONE'], true),
            'new_user_group' => $requestData['ACCESS_LEVEL'],
        ];
        $webServiceQueue->add('SetDataUser', '', $fields);
    }

    protected function addHistoryItem($userId)
    {
        global $USER;
        $actionHistoryRepository = new ActionHistoryRepository();

        $actionHistoryRepository->addHistoryItem(
            $userId,
            'Регистрация пользователя на портале',
            'РЕГИС-',
            $userId,
            $USER->GetID()
        );
    }

    protected function sendMail($outputNumber, $requestData, $userFields, $userId)
    {
        global $USER;
        $file = new \CFile();
        $user = new \CUser();
        $event = new \CEvent();
        $elementManager = new ElementManager();
        $userRepository = new UserRepository();
        $managerData = [];
        $userData = $userRepository->findOneWhere(['ID' => $userId], [
            'UF_FILE',
        ]);
        $fileData = $file->GetFileArray($userData['UF_FILE']);
        $fileString = ($fileData) ? '<a href="https://' . SITE_SERVER_NAME . $fileData['SRC'] . '">' . $fileData['FILE_NAME'] . '</a>' : '';

        if ($this->arResult['IS_MANAGER'] || $this->arResult['IS_AUL']) {
            $managerData = $userRepository->findOneWhere(['ID' => $USER->GetID()], [
                'EMAIL',
                'XML_ID',
                'FULL_NAME',
            ]);
        }
        $managerWord = ($this->arResult['IS_AUL']) ? 'Администратор ЮЛ' : 'Менеджер';
        $eventFields = [
            'UF_FILE' => $fileString,
            'MANAGER_WORD' => ($this->arResult['IS_AUL']) ? 'администратора ЮЛ' : 'менеджера',
            'GUID_MANAGER' => $managerData['XML_ID'],
            'FIO_MANAGER' => $managerData['FULL_NAME'],
            'EMAIL_MANAGER' => $managerData['EMAIL'],
            'OUTPUT_NUMBER' => $outputNumber,
            'PHONE' => $elementManager->stylePhone($requestData['PHONE']),
            'EMAIL' => $requestData['EMAIL'],
            'NAME' => $requestData['NAME'],
            'RS_DATE_CREATE' => date('d.m.Y H:i:s'),
            'RS_FORM_NAME' => 'Регистрация на клиентском портале ТехноНиколь',
            'USER_TYPE' => ($this->arParams['FORM_CODE'] == 'REGISTER_UL') ? 'Юр. лицо' : 'Физ. лицо',
            'TEXT' => ($this->arResult['IS_MANAGER'] || $this->arResult['IS_AUL'])
                ? $managerWord . ' подал заявку на регистрацию пользователя на клиентском портале № ' . $outputNumber . '.'
                : 'Пользователь подал заявку на регистрацию на клиентском портале № ' . $outputNumber . '.',
        ];
        $eventFields = array_merge($eventFields, $userFields);
        $eventFields = $this->changeEnumValuesToReadable($eventFields, $userFields);
        $eventFields = $this->addContractorData($eventFields);

        $event->Send('PORTAL_REGISTRATION_ADMIN', SITE_ID, $eventFields);
        if ($this->arParams['FORM_CODE'] == 'REGISTER_UL') {
            $event->Send('PORTAL_REGISTRATION_UL', SITE_ID, $eventFields);
        } else {
            $event->Send('PORTAL_REGISTRATION_FL', SITE_ID, $eventFields);
        }

        if ($this->arResult['IS_AUL']) {
            $checkWord = $this->randString(10);
            $user->Update($userId, ['CHECKWORD' => $checkWord]);
            $event->Send('PORTAL_USER_ACTIVATION', 's1', [
                'CHECKWORD' => $checkWord,
                'EMAIL' => $requestData['EMAIL'],
                'ID' => $userId,
            ]);
        }
    }

    protected function changeEnumValuesToReadable($eventFields, $userFields)
    {
        $eventFields['UF_LEGAL_FORM'] = ($eventFields['UF_OTHER_LEGAL_FORM'])
            ?: $this->arResult['ENUM']['UF_LEGAL_FORM'][$userFields['UF_LEGAL_FORM']]['VALUE'];
        $eventFields['UF_WORK'] = ($eventFields['UF_OTHER_WORK'])
            ?: $this->arResult['ENUM']['UF_WORK'][$userFields['UF_WORK']]['VALUE'];
        if ($userFields['UF_REG_CLIENT_TYPE']) {
            $clientTypeEnum = $this->arResult['ENUM']['UF_REG_CLIENT_TYPE'][$userFields['UF_REG_CLIENT_TYPE']];
            $eventFields['UF_REG_CLIENT_TYPE'] = $clientTypeEnum['VALUE'] . ' (' . $clientTypeEnum['XML_ID'] . ')';
        }

        return $eventFields;
    }

    protected function addContractorData($eventFields)
    {
        if (!$this->arParams['CONTRACTOR_GUID']) {
            return $eventFields;
        }
        $contractorRepository = new ContractorRepository();
        $contractorData = $contractorRepository->findOneWhere([
            $contractorRepository->getPrimaryKey() => $this->arParams['CONTRACTOR_GUID']
        ]);
        if (!$contractorData) {
            return $eventFields;
        }
        $eventFields['UF_INN'] = $contractorData['cash_contractor_inn'];
        $eventFields['UF_ADDRESS_LEGAL'] = $contractorData['cash_contractor_address_legal'];
        $eventFields['UF_ADDRESS_FACT'] = $contractorData['cash_contractor_address_actual'];
        $eventFields['UF_COMPANY_PHONE'] = $contractorData['cash_contractor_phone'];
        $eventFields['COMPANY_NAME'] = $contractorData['cash_contractor_name'];
        $eventFields['GUID_COMPANY'] = $this->arParams['CONTRACTOR_GUID'];
        return $eventFields;
    }
}