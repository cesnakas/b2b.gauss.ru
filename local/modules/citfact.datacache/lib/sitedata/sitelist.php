<?php
namespace Citfact\DataCache\SiteData;

use Citfact\DataCache\DataID,
    Bitrix\Main\SiteTable;

class SiteList extends DataID
{
    protected $codeCache = 'sitelist';

    /**
     * кешированный спсиок сайтов
     *
     * return $dataByCode = array('CODE' => array())
     */
    protected function setData()
    {
        $sideDomainByLID = array();

        $resSite = SiteTable::getList(array(
            'filter' => array('!=SERVER_NAME' => 'false'),
            'select' => array('*'),
        ));
        while ($arSite = $resSite->fetch()) {
            $sideDomainByLID[$arSite['LID']] = $arSite;
        }

        return $sideDomainByLID;
    }
}



