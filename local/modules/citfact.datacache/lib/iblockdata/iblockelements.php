<?php

namespace Citfact\DataCache\IBlockData;

use Bitrix\Main\Loader;
use Citfact\DataCache\DataID;

/**
 * Кэширует выборку из инфоблока.
 * Class IblockElements
 * @package Citfact\DataCache\IBlockData
 */
class IblockElements extends DataID
{
    protected $codeCache = 'iBlockElements';
    protected $iblockId = 0;
    protected $filter = [];
    protected $select = [];
    protected $needAllProps = true;
    protected $tagName;

    /**
     * IblockElements constructor.
     * @param $iblockId
     * @param array $filter
     * @param array $select
     * @param bool $needAllProps
     * @throws \Bitrix\Main\LoaderException
     * @throws \ErrorException
     */
    public function __construct($iblockId, $filter = [], $select = [], $needAllProps = true)
    {
        if (empty($iblockId)) {
            throw new \ErrorException('Incorrect param iblockId');
        }

        if (!Loader::IncludeModule('iblock')) {
            throw new \ErrorException('Can not include iblock module');
        }

        parent::__construct();

        $this->cache = new \CPHPCache();
        // id кеша строится по IBLOCK_ID и сериализованным данным фильтра
        $this->cache_id = $this->codeCache.'Data'.$iblockId.md5(serialize(array_merge($filter, $select)));
        $this->cache_path = '/'.$this->codeCache.'Data'.$iblockId.'/';
        $this->cache_time = 86400 * 30;

        $this->iblockId = $iblockId;
        $this->filter = $filter;
        $this->select = $select;
        $this->needAllProps = $needAllProps;

        // тэг для кэша. По такому тэгу кэш автоматически сбросится при изменении
        // элементов инфоблока стандартными методами Add, Update, Delete.
        // При изменении свойств элемента ифноблока (например методом CIBlockElement::SetPropertyValuesEx())
        // кэш нужно принудительно сбросить методом clearIblockCache()
        $this->tagName = 'iblock_id_'.$this->iblockId;
    }

    /**
     * {@inheritdoc}
     */
    protected function setData()
    {
        $arFilter = ["IBLOCK_ID" => $this->iblockId];
        $arSelect = ["ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "CODE", "NAME", "POPERTY_*"];

        if (!empty($this->select)) {
            $arSelect = $this->select;
        }

        $arResult = [];

        $ob = \CIBlockElement::GetList(
            ['SORT' => 'ASC'],
            array_merge($arFilter, $this->filter),
            false,
            false,
            $arSelect
        );

        while ($res = $ob->GetNextElement()) {

            $arFields = $res->GetFields();

            if ($this->needAllProps) {
                $arProps = $res->GetProperties();

                if (!empty($arProps)) {
                    $arFields["PROPERTIES"] = $arProps;
                }
            }

            $arResult[] = $arFields;
        }

        return $arResult;
    }

    /**
     * {@inheritdoc}
     */
    protected function setTagCache() {
        parent::setTagCache($this->tagName);
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function getById($id)
    {
        if (empty($this->byCode)) {
            $this->getData();
        }

        foreach ($this->byCode as $arItem) {
            if ($arItem['ID'] == $id) {
                return $arItem;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getByCode($code)
    {
        if (empty($this->byCode)) {
            $this->getData();
        }

        foreach ($this->byCode as $arItem) {
            if ($arItem['CODE'] == $code) {
                return $arItem;
            }
        }

        return false;
    }

    /**
     * Метод для принудительного сброса кеша
     */
    public function clearIblockCache () {
        $GLOBALS['CACHE_MANAGER']->ClearByTag($this->tagName);
    }

}
