<?php
namespace Citfact\DataCache\IBlockData;

use Citfact\DataCache\DataID;

use Bitrix\Main\Loader;

/**
 * This file is part of the Studio Fact package.
 * @package citfact
 * @copyright 2017 Studio Fact
 */

class IBlockProperties extends DataID
{
    protected $codeCache = 'iBlockProperties';
    protected $iBlock = 0;

    public function __construct($iBlock)
    {
        if (empty($iBlock) || !Loader::IncludeModule('iblock')) {
            throw new \ErrorException('Incorrect params iblock');
        }

        parent::__construct();
        $this->cache = new \CPHPCache();
        $this->cache_id = $this->codeCache.'Data'.$iBlock;
        $this->cache_path = '/'.$this->cache_id.'/';
        $this->iBlock = $iBlock;
    }

    /**
     * @return array
     * Возвращает массив свойства типа список
     * @return array['CODE'] = 'ID';
     */
    protected function setData()
    {
        $arValuesReturn = array();
        $properties = \CIBlockProperty::GetList(Array(), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$this->iBlock));
        while ($prop_fields = $properties->GetNext())
        {
            $arValuesReturn[$prop_fields['CODE']] = $prop_fields['ID'];
        }
        return $arValuesReturn;
    }
}