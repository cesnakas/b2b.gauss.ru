<?php
namespace Citfact\DataCache\SiteData;

use Citfact\DataCache\DataID,
    Bitrix\Main\SiteTable;

class SiteDomains extends DataID
{
    protected $codeCache = 'sitedomains';

    /**
     * список доменов
     *
     * return $dataByCode = array('CODE' => 'ID')
     */
    protected function setData()
    {
        $sideDomainByLID = array();

        $resSite = SiteTable::getList(array(
            'filter' => array('!=SERVER_NAME' => 'false'),
            'select' => array('SERVER_NAME', 'LID'),
        ));
        while ($arSite = $resSite->fetch()) {
            $sideDomainByLID[$arSite['LID']] = $arSite['SERVER_NAME'];
        }

        return $sideDomainByLID;
    }
}



