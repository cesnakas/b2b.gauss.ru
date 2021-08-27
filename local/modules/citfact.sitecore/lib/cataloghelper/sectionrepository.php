<?php

namespace Citfact\SiteCore\CatalogHelper;

class SectionRepository
{
    /**
     * @param array $sectionIds
     * @param int $iblockId
     * @return array
     */
    public function getSectionsByIds($sectionIds, $iblockId)
    {
        if (!$sectionIds) {
            return array();
        }
        $filter = array(
            'IBLOCK_ID' => $iblockId,
            'ACTIVE' => 'Y',
            'ID' => $sectionIds,
        );

        return $this->getSections($filter);
    }

    /**
     * @param array $filter
     * @return array
     */
    private function getSections($filter)
    {
        $result = [];
        $cSection = new \CIBlockSection();
        $select = [
            'ID',
            'PICTURE',
            'NAME',
            'SECTION_PAGE_URL',
            'DEPTH_LEVEL',
            'IBLOCK_SECTION_ID',
        ];

        $res = $cSection->GetList(['SORT' => 'ASC', 'ID' => 'DESC'], $filter, false, $select);
        while ($obj = $res->GetNextElement()) {
            $item = $obj->GetFields();
            $result[$item['ID']] = $item;
        }

        return $result;
    }

    /**
     * @param int $sectionId
     * @param int $iblockId
     * @return array
     */
    public function getWithChildSections($sectionId, $iblockId)
    {
        $arFilter = array(
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockId,
            'GLOBAL_ACTIVE'=>'Y',
        );
        $arSelect = array('IBLOCK_ID','ID','NAME','DEPTH_LEVEL','IBLOCK_SECTION_ID');
        $arOrder = array('DEPTH_LEVEL'=>'ASC','SORT'=>'ASC');
        $cSection = new \CIBlockSection();
        $rsSections = $cSection::GetList($arOrder, $arFilter, false, $arSelect);
        $sectionLinc = array();
        while($arSection = $rsSections->GetNext()) {
            if($arSection['ID'] == $sectionId || $arSection['IBLOCK_SECTION_ID'] == $sectionId) {

                $sectionLinc[] = $arSection['ID'] ? $arSection['ID'] : $sectionLinc[$arSection['IBLOCK_SECTION_ID']];
            }
        }

        return $sectionLinc;
    }
}