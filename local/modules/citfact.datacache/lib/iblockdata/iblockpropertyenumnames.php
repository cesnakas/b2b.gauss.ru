<?php
namespace Citfact\DataCache\IBlockData;

use Citfact\DataCache\DataID;

use Bitrix\Main\Loader;

/**
 * This file is part of the Studio Fact package.
 * @package citfact
 * @copyright 2017 Studio Fact
 */

class IBlockPropertyEnumNames extends DataID
{
    protected $codeCache = 'iBlockPropEnumNames';
    protected $iBlock = 0;
    protected $code = '';

    public function __construct($iBlock, $code)
    {
        if (empty($code) || empty($iBlock) || !Loader::IncludeModule('iblock')) {
            throw new \ErrorException('Incorrect params');
        }

        parent::__construct();
        $this->cache = new \CPHPCache();
        $this->cache_id = $this->codeCache.'Data'.$iBlock.$code;
        $this->cache_path = '/'.$this->cache_id.'/';
        $this->code = $code;
        $this->iBlock = $iBlock;
    }

    /**
     * @return array
     * Возвращает массив свойства типа список
     * @return array['CODE'] = 'VALUE';
     */
    protected function setData()
    {
        $arValuesReturn = array();
        $property_enums = \CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID" => $this->iBlock, "CODE"=>$this->code));
        while($enum_fields = $property_enums->Fetch())
        {
            $arValuesReturn[$enum_fields['XML_ID']] = $enum_fields['VALUE'];
        }
        return $arValuesReturn;
    }
}