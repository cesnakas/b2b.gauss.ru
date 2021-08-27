<?php
namespace Citfact\DataCache\HLBlockData;

use Citfact\DataCache\DataID;

class DictionaryEnumNames extends DataID
{
    protected $codeCache = 'HLBlockEnumNames';
    protected $HLBlockID;
    protected $fieldName;

    public function __construct ($HLBlockID, $fieldName) {
        if (empty($HLBlockID)) {
            throw new \Exception('Please, set HLBlock ID');
        }

        if (empty($fieldName)) {
            throw new \Exception('Please, set field Name');
        }

        parent::__construct();
        $this->HLBlockID = (int)$HLBlockID;
        $this->fieldName = $fieldName;
        $this->cache_id = $this->codeCache.'Data'.$this->HLBlockID.$this->fieldName;
        $this->cache_path = '/'.$this->codeCache.'/Data'.$this->HLBlockID.$this->fieldName.'/';
    }

    /**
     * return $dataByCode = array('CODE' => 'ID')
     */
    protected function setData()
    {
        if (!\Bitrix\Main\Loader::includeModule('highloadblock'))
            return array();

        $dictionary = array();
        $userFieldEnum = new \CUserFieldEnum;
        $rsData = \CUserTypeEntity::GetList(
            array(),
            array(
                'ENTITY_ID ' => 'HLBLOCK_'.$this->HLBlockID,
                'FIELD_NAME' => $this->fieldName
            )
        );
        if($arRes = $rsData->Fetch())
        {
            $rsValues = $userFieldEnum->GetList(array(), array('USER_FIELD_ID' => $arRes['ID']));
            foreach($rsValues->arResult as $value)
            {
                $dictionary[$value['XML_ID']] = array(
                    'ID' => $value['ID'],
                    'VALUE' => $value['VALUE'],
                    'XML_ID' => $value['XML_ID'],
                );
            }
        }
        return $dictionary;
    }
}
