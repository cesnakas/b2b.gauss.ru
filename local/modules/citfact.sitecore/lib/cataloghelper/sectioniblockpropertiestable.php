<?php
namespace Citfact\Sitecore\CatalogHelper;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\Core;
use Exception;

Loc::loadMessages(__FILE__);

/**
 * Class getSectionIblockPropertiesTable
 *
 * Fields:
 * <ul>
 * <li> VALUE_ID int mandatory
 * <li> UF_IBLOCK_PROPERTIES string optional
 * </ul>
 *
 * @package Bitrix\Uts
 **/

class SectionIblockPropertiesTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     * @throws Exception
     */
    public static function getTableName()
    {

        $core = Core::getInstance();
        $iblockId = $core->getIblockId($core::IBLOCK_CODE_CATALOG);

        return 'b_uts_iblock_' . $iblockId . '_section';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'VALUE_ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'title' => 'VALUE_ID',
            ),
            'UF_IBLOCK_PROPERTIES' => array(
                'data_type' => 'text',
                'title' => 'UF_IBLOCK_PROPERTIES',
            ),
        );
    }

    public static function getProperties($sectionId, $ufPropertyCode)
    {
        $connection = \Bitrix\Main\Application::getConnection();
        $tableName = $connection->isTableExists(self::getTableName());

        if ($connection->isTableExists($tableName) === false) {
            return false;
        }
        
        $sectionPropertiesDB = sectionIblockPropertiesTable::getList([
            'select' => [$ufPropertyCode],
            'filter' => ['VALUE_ID' => $sectionId]
        ]);

        $sectionProperties = unserialize($sectionPropertiesDB->fetch()['UF_IBLOCK_PROPERTIES']);

        return $sectionProperties;
    }
}