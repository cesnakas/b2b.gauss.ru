<?php

namespace Citfact\Sitecore\CatalogHelper;

class ElementRepository
{
    /**
     * @param $iblockId
     * @param $filter
     * @return array
     */
    public function getYearsInterval($iblockId, $filter)
    {
        if (!$filter) {
            $filter = array('ACTIVE' => 'Y');
        }
        $filter['IBLOCK_ID'] = $iblockId;
        $sort = array(
            'ACTIVE_FROM' => 'DESC'
        );
        $item = $this->getNextElement($filter, $sort);
        $yearEnd = substr($item['ACTIVE_FROM'], 6, 4);

        $sort = array(
            'ACTIVE_FROM' => 'ASC'
        );
        $item = $this->getNextElement($filter, $sort);
        $yearStart = substr($item['ACTIVE_FROM'], 6, 4);

        return array(
            'START' => $yearStart,
            'END' => $yearEnd,
        );
    }



    /**
     * @param $filter
     * @param $sort
     * @return array
     */
    public function getNextElement($filter, $sort = array())
    {
        $result = array();

        $cElement = new \CIBlockElement();
        $select = array(
            'ID',
            'DETAIL_PAGE_URL',
            'SORT',
            'ACTIVE_FROM',
        );
        if (!$sort) {
            $sort = array(
                'SORT' => 'ASC',
                'ACTIVE_FROM' => 'ASC'
            );
        }
        $res = $cElement->GetList($sort, $filter, false, array('nTopCount' => 1), $select);
        while ($object = $res->GetNextElement()) {
            $result = $object->GetFields();
        }

        return $result;
    }


    public function getIblockActiveSections($iblockId)
    {
        $element = new \CIBlockElement();
        $sectionRepository = new SectionRepository();
        $filter = array(
            'IBLOCK_ID' => $iblockId,
        );
        $select = array(
            'IBLOCK_SECTION_ID',
        );
        $res = $element->GetList(array(), $filter, ['IBLOCK_SECTION_ID'], false, $select);
        $sectionIds = [];
        while ($item = $res->Fetch()) {
            $sectionIds[] = $item['IBLOCK_SECTION_ID'];
        }
        return $sectionRepository->getSectionsByIds($sectionIds, $iblockId);
    }

    public function getCountElementsSection($sectionId, $iblockId)
    {
        $element = new \CIBlockElement();
        $sectionRepository = new SectionRepository();

        $sections = $sectionRepository->getWithChildSections($sectionId, $iblockId);

        if ($sections) {
            $filter = array(
                'IBLOCK_ID' => $iblockId,
                'IBLOCK_SECTION_ID' => $sections,
            );
            $select = array();
            $res = $element->GetList(array(), $filter, ['IBLOCK_SECTION_ID'], false, $select);
            $sectionIds = 0;

            while ($item = $res->Fetch()) {

                $sectionIds += intval($item['CNT']);
            }
        }else{
            $sectionIds = [];
        }
        return $sectionIds;
    }

    /**
     * @param array $itemIds
     * @return array
     */
    public function getElementGroups($itemIds)
    {
        $cElement = new \CIBlockElement();

        $result = array();
        $res = $cElement->GetElementGroups(
            $itemIds,
            true,
            array('ID', 'IBLOCK_ELEMENT_ID')
        );
        while ($group = $res->Fetch()) {
            $result[$group['IBLOCK_ELEMENT_ID']][$group['ID']] = $group['ID'];
        }

        return $result;
    }


    /**
     * @param $item
     * @param $section
     * @return array
     */
    public function getAdditionalInfo($item, $section = array())
    {
        $arSectionsIds = [];
        foreach ($section['PATH'] as $arSection){
            $arSectionsIds[] = $arSection['ID'];
        }


        if ($item['MIN_PRICE']['VALUE']) {
            $item['PRICE'] = $this->formatPrice($item['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);
            $item['RAW_PRICE'] = str_replace(' ', '', $item['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);
        }
        foreach ($item['PRICES'] as $key => $price) {
            if ($price['PRINT_DISCOUNT_VALUE']) {
                $item['PRICES'][$key]['PRINT_DISCOUNT_VALUE'] = $this->formatPrice($price['PRINT_DISCOUNT_VALUE']);
            }

            // Старая цена записывается, если у элемента есть и акционная, и старая цены
//            if (array_key_exists(Price::PRICE_CODE_ACTION, $item['PRICES']) && array_key_exists(Price::PRICE_CODE_MAIN, $item['PRICES'])) {
//                $item['PRICE_MAIN'] = $item['PRICES'][Price::PRICE_CODE_MAIN]['PRINT_DISCOUNT_VALUE'];
//                $item['RAW_PRICE_MAIN'] = str_replace(' ', '', $item['PRICES'][Price::PRICE_CODE_MAIN]['PRINT_DISCOUNT_VALUE']);
//            }
        }

        return $item;
    }


    /**
     * @param $arProductIds
     * @return array
     */
    public function getMeasure($arProductIds){
        if (empty($arProductIds)){
            return [];
        }

        $db_res = \CCatalogProduct::GetList(
            array(),
            array("ID" => $arProductIds),
            false,
            false,
            array('ID', 'MEASURE')
        );
        $arMeasures = [];
        while ($arRes = $db_res->Fetch())
        {
            $arMeasures[$arRes['ID']] = \CCatalogMeasure::getList(array(), array('ID' => $arRes['MEASURE']))->GetNext();
        }

        return $arMeasures;
    }


    /**
     * @param $price
     * @return string
     */
    public function formatPrice($price)
    {
        $price = preg_replace("/[^0-9,.]/", "", $price); //удаляет нечисловые символы
        if (!$price) {
            return '';
        }
        if (strpos($price, '.')) {
            $price = explode('.', $price);
        } elseif(strpos($price, ',')) {
            $price = explode(',', $price);
        }

        if (!$price[1]) {
            $price[1] = '00';
        }

        if (strlen($price[1]) == 1) {
            $price[1] = $price[1] . '0';
        }

        if (is_array($price)) {
            return $price[0] . " <sup>" . $price[1] . "</sup>";
        } else {
            return $price;
        }
    }

    public static function formatFullPrice($price, $currency)
    {
        return CurrencyFormat($price, $currency);
    }
}