<?

namespace Citfact;

use Citfact\Core\CatalogHelper\ItemCounter;

class OptimusCustom
{
    public function getChainNeighbors($curSectionID, $chainPath)
    {
        $cSection = new \CIBlockSection();

        $cItemCounter = new ItemCounter();

        $arResult =
        $sectionIds =
        $arSections =
        $arSectionsIDs =
        $arSubSections = array();

        $IBLOCK_ID = false;
        $nav = $cSection->GetNavChain(
            false,
            $curSectionID,
            array(
                "ID",
                "IBLOCK_ID",
                "IBLOCK_SECTION_ID",
                "SECTION_PAGE_URL"
            )
        );
        while ($ar = $nav->GetNext()) {
            $arSections[] = $ar;
            $arSectionsIDs[] = ($ar["IBLOCK_SECTION_ID"] ? $ar["IBLOCK_SECTION_ID"] : 0);
            $IBLOCK_ID = $ar["IBLOCK_ID"];
        }

        if ($arSectionsIDs) {
            $resSubSection = $cSection->GetList(
                array('SORT' => 'ASC'),
                array(
                    "ACTIVE" => "Y",
                    "GLOBAL_ACTIVE" => "Y",
                    "IBLOCK_ID" => $IBLOCK_ID,
                    "SECTION_ID" => $arSectionsIDs
                ),
                false,
                array("ID", "NAME", "IBLOCK_SECTION_ID", "SECTION_PAGE_URL")
            );
            while ($arSubSection = $resSubSection->GetNext()) {
                $arSubSection["IBLOCK_SECTION_ID"] = ($arSubSection["IBLOCK_SECTION_ID"]
                    ? $arSubSection["IBLOCK_SECTION_ID"] : 0);
                $arSubSections[$arSubSection["IBLOCK_SECTION_ID"]][] = $arSubSection;
                $sectionIds[$arSubSection['ID']] = $arSubSection['ID'];
            }

            if (in_array(0, $arSectionsIDs)) {
                $resSubSection = $cSection->GetList(
                    array('SORT' => 'ASC'),
                    array(
                        "ACTIVE" => "Y",
                        "GLOBAL_ACTIVE" => "Y",
                        "IBLOCK_ID" => $IBLOCK_ID,
                        "SECTION_ID" => false
                    ),
                    false,
                    array("ID", "NAME", "IBLOCK_SECTION_ID", "SECTION_PAGE_URL")
                );
                while ($arSubSection = $resSubSection->GetNext()) {
                    $arSubSections[$arSubSection["IBLOCK_SECTION_ID"]][] = $arSubSection;
                    $sectionIds[$arSubSection['ID']] = $arSubSection['ID'];
                }
            }
        }
        $sectionIds = array();
        if ($arSections && strlen($chainPath)) {
            foreach ($arSections as $arSection) {
                if (
                !$arSubSections[$arSection["IBLOCK_SECTION_ID"]]
                ) {
                    continue;
                }

                foreach ($arSubSections[$arSection["IBLOCK_SECTION_ID"]] as $arSubSection) {
                    if (
                        $curSectionID == $arSubSection["ID"] ||
                        !$cItemCounter->getCountBySectionId($arSubSection["ID"])
                    ) {
                        continue;
                    }
                    $arResult[$arSection["SECTION_PAGE_URL"]][] = array(
                        "NAME" => $arSubSection["NAME"],
                        "LINK" => $arSubSection["SECTION_PAGE_URL"]
                    );
                    $sectionIds[$arSubSection['ID']] = $arSubSection['ID'];
                }
            }
        }

        return $arResult;
    }
}

