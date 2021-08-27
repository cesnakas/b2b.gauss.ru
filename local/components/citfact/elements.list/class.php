<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Iblock\InheritedProperty\SectionValues;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Citfact\Tools\ElementManager;

Loader::includeModule('iblock');
Loc::loadMessages(__FILE__);

class ElementsListComponent extends \CBitrixComponent
{
    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        global $USER;
        Loc::loadMessages(__FILE__);
        $iblockElement = new \CIBlockElement();
        $iblockSection = new \CIBlockSection();

        $iblockId = (int)$this->arParams['IBLOCK_ID'];
        if ($this->StartResultCache(false, $USER->GetGroups()) && $iblockId != '') {
            if (!empty($this->arParams['PROPERTY_CODES'])) {
                foreach ($this->arParams['PROPERTY_CODES'] as &$propCode) {
                    $propCode = 'PROPERTY_' . $propCode;
                }
            }

            $order = ['SORT' => 'ASC', 'NAME' => 'ASC'];
            if (!empty($this->arParams['SORT'])) {
                $order = $this->arParams['SORT'];
            }
            $filter = array('IBLOCK_ID' => $this->arParams['IBLOCK_ID'], 'ACTIVE' => 'Y',
                // Фильтр по дате: с пустой датой завершения активности или с датой завершения больше текущей даты
                array(
                    'LOGIC' => 'OR',
                    array('DATE_ACTIVE_TO' => false),
                    array('>DATE_ACTIVE_TO' => ConvertTimeStamp(time(), 'FULL'))
                ),
                array(
                    'LOGIC' => 'OR',
                    array('SECTION_GLOBAL_ACTIVE' => 'Y'),
                    array('SECTION_ID' => false)
                )
            );
            if ($this->arParams['FILTER'] && is_array($this->arParams['FILTER'])) {
                $filter = array_merge($filter, $this->arParams['FILTER']);
            }
            $select = ['ID', 'IBLOCK_ID', 'ACTIVE', 'NAME', 'CODE', 'IBLOCK_SECTION_ID'];
            if ($this->arParams['FIELDS'] && is_array($this->arParams['FIELDS'])) {
                $select = array_merge($select, $this->arParams['FIELDS']);
            }
            if ($this->arParams['PROPERTY_CODES'] && is_array($this->arParams['PROPERTY_CODES'])) {
                $select = array_merge($select, $this->arParams['PROPERTY_CODES']);
            }
            $navParams = false;
            if ((int)$this->arParams['ELEMENTS_COUNT'] > 0) {
                $navParams = array('nTopCount' => (int)$this->arParams['ELEMENTS_COUNT']);
            }

            if (isset($filter['ID']) && empty($filter['ID'])) {
                throw new \Exception('Filter by ID is empty');
            }
            $res = $iblockElement->GetList($order, $filter, false, $navParams, $select);
            $this->arResult['ITEMS'] = array();
            $sectionIds = array();
            $this->arResult['ITEM_IDS'] = array();
            if (in_array('PROPERTY_*', $select)) {
                $this->connectItemsWithAllProperties($res);
            } else {
                $this->connectItems($res);
            }


            if (!empty($this->arResult['ITEM_IDS'])) {
                $res = CIBlockElement::GetElementGroups($this->arResult['ITEM_IDS'], false, array('ID', 'IBLOCK_SECTION_ID'));
                while ($item = $res->Fetch()) {
                    $sectionIds[] = $item['ID'];
                }
            }

            $menuItems = array();

            if (!empty($sectionIds)) {
                $res = $iblockSection->GetList(
                    array('SORT' => 'ASC', 'NAME' => 'ASC'),
                    array('ID' => $sectionIds, 'IBLOCK_ID' => $this->arParams['IBLOCK_ID'], 'GLOBAL_ACTIVE' => 'Y'),
                    false,
                    array('ID', 'NAME', 'CODE', 'SORT', 'IBLOCK_SECTION_ID')
                );
                $sections = array();
                while ($item = $res->GetNext()) {
                    $sections[$item['ID']]['NAME'] = $item['NAME'];
                    $sections[$item['ID']]['IBLOCK_SECTION_ID'] = $item['IBLOCK_SECTION_ID'];

                    $temp = array(
                        'NAME' => $item['NAME'],
                        'CODE' => $item['CODE'],
                        'SORT' => $item['SORT'],
                    );
                    $menuItems[$item['ID']] = $temp;
                }

                foreach ($this->arResult['ITEMS'] as $item) {
                    $sectionId = $item['IBLOCK_SECTION_ID'] != '' ? $item['IBLOCK_SECTION_ID'] : '0';
                    $sections[$sectionId]['ITEMS'][] = $item;

                    $temp = array(
                        'NAME' => $item['NAME'],
                        'CODE' => $item['CODE'],
                        'SORT' => $item['SORT'],
                        'LINK' => $item['PROPERTY_LINK_VALUE'],
                    );
                    if ($sectionId != '0') {
                        $menuItems[$sectionId]['ITEMS'][] = $temp;
                    } else {
                        $menuItems[$item['ID']] = $temp;
                    }
                }
                $this->arResult['SECTIONS'] = $sections;

                if (!function_exists('cmp_by_sort')) {
                    function cmp_by_sort($a, $b)
                    {
                        if ($a['SORT'] == $b['SORT']) {
                            return 0;
                        }
                        return ($a['SORT'] < $b['SORT']) ? -1 : 1;
                    }
                }
                uasort($menuItems, 'cmp_by_sort');
            } else {
                $menuItems = $this->arResult['ITEMS'];
            }
            $this->arResult['MENU_ITEMS'] = $menuItems;

            $this->IncludeComponentTemplate();
        }

        $this->setTitle();
        $this->addChainItems();

        return $this->arResult;
    }

    /**
     * @param integer|CIBlockResult $res
     */
    private function connectItemsWithAllProperties($res)
    {
        while ($object = $res->GetNextElement()) {
            $properties = $object->GetProperties();
            $item = $object->GetFields();
            $item = $this->connectEditLink($item);
            foreach ($properties as $property) {
                $this->arResult['PROPERTIES'][$property['CODE']] = $property;
                if ($property['VALUE']) {
                    $item['~PROPERTY_' . $property['CODE'] . '_VALUE'] = $property['~VALUE'];
                    $item['PROPERTY_' . $property['CODE'] . '_VALUE'] = $property['VALUE'];
                    $item['PROPERTY_' . $property['CODE'] . '_VALUE_XML_ID'] = $property['VALUE_XML_ID'];
                }
            }
            $this->arResult['ITEMS'][] = $item;
            $this->arResult['ITEM_IDS'][] = $item['ID'];
        }
    }

    /**
     * @param integer|CIBlockResult $res
     */
    private function connectItems($res)
    {
        while ($item = $res->GetNext()) {
            $item = $this->connectEditLink($item);
            $this->arResult['ITEMS'][] = $item;
            $this->arResult['ITEM_IDS'][] = $item['ID'];
        }
    }


    private function connectEditLink($item)
    {
        $buttons = CIBlock::GetPanelButtons(
            $item['IBLOCK_ID'],
            $item['ID'],
            0,
            ['SECTION_BUTTONS' => false, 'SESSID' => false]
        );
        $item['EDIT_LINK'] = $buttons['edit']['edit_element']['ACTION_URL'];
        $item['DELETE_LINK'] = $buttons['edit']['delete_element']['ACTION_URL'];

        return $item;
    }

    private function setTitle()
    {
        if ($this->arParams['SET_SECTION_TITLE'] == 'Y' && $this->arResult['SECTION']['NAME']) {
            $iPropValues = new SectionValues($this->arParams['IBLOCK_ID'], $this->arResult['SECTION']['ID']);
            $ipropertyValues = $iPropValues->getValues();
            $elementManager = new ElementManager();
            $elementManager->setIpropValues($ipropertyValues, $this->arResult['SECTION']['NAME']);
        }
    }

    protected function addChainItems()
    {
        global $APPLICATION;
        if (
            $this->arParams['ADD_SECTIONS_CHAIN'] &&
            isset($this->arResult['SECTION'])
        ) {

            $this->arResult['SECTION']['PATH'] = array();
            $rsPath = CIBlockSection::GetNavChain($this->arResult['SECTION']['IBLOCK_ID'], $this->arResult['SECTION']['ID']);
            while ($path = $rsPath->GetNext()) {
                $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($this->arParams['IBLOCK_ID'], $path['ID']);
                $path['IPROPERTY_VALUES'] = $ipropValues->getValues();
                $this->arResult['SECTION']['PATH'][] = $path;
                if (
                    isset($path['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']) &&
                    $path['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] != ''
                ) {
                    $APPLICATION->AddChainItem($path['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'], $path['~SECTION_PAGE_URL']);
                } else {
                    $APPLICATION->AddChainItem($path['NAME'], $path['~SECTION_PAGE_URL']);
                }
            }
        }
    }

    /**
     * @param $arParams
     * @return array
     * @throws Exception
     */
    public function onPrepareComponentParams($arParams)
    {
        $cIblock = new \CIBlock();
        $arParams = array_merge($arParams, array(
            'CACHE_TYPE' => isset($arParams['CACHE_TYPE']) ? $arParams['CACHE_TYPE'] : 'A',
            'CACHE_TIME' => isset($arParams['CACHE_TIME']) ? $arParams['CACHE_TIME'] : 36000000,
        ));

        if (!$arParams['IBLOCK_ID'] && $arParams['IBLOCK_CODE']) {
            $iblock = $cIblock->GetList(
                array(),
                array('CODE' => $arParams['IBLOCK_CODE'])
            )->Fetch();

            if ($iblock['ID']) {
                $arParams['IBLOCK_ID'] = $iblock['ID'];
            }
        }
        if (!$arParams['IBLOCK_ID']) {
            throw new Exception('Empty IBLOCK_ID param.');
        }

        return $arParams;
    }
}