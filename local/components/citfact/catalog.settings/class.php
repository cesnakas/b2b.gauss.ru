<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class JurSettingsComponent extends CBitrixComponent
{
    /**
     * @return void
     */
    protected function checkSortOrSetDefault()
    {
        global $APPLICATION;

        $request = Application::getInstance()->getContext()->getRequest();
       
        $sortOrder =
        $sort = '';

        if ($request->getQuery('sort')) {
            $sort = $request->getQuery('sort') ?: '';
            $_SESSION['sort'] = ToLower($_REQUEST['sort']);
        } elseif ($_SESSION['sort']) {
            $sort = ToLower($_SESSION['sort']);
        }

        if ($request->getQuery('order')) {
            $sortOrder = in_array($request->getQuery('order'), array('asc', 'desc'))
                ? $request->getQuery('order')
                : 'asc';
            $_SESSION['order'] = ToLower($_REQUEST['order']);
        } elseif ($_SESSION['order']) {
            $sortOrder = ToLower($_SESSION['order']);
        }

        $isFindSort = false;

        foreach ($this->arResult['SORT'] as $key => $arSort) {
            $urlFragment = sprintf('sort=%s&order=%s', $arSort['KEY'], $arSort['ORDER']);
            $this->arResult['SORT'][$key]['URL'] = $APPLICATION->GetCurPageParam($urlFragment, array('sort', 'order'), false);

            if ($arSort['KEY'] == $sort && $arSort['ORDER'] == $sortOrder) {
                $isFindSort = true;
                $this->arResult['SORT'][$key]['ACTIVE'] = 'Y';
            }
        }

        if ($isFindSort === false) {
            $this->arResult['SORT'][0]['ACTIVE'] = 'Y';
        }
    }

    /**
     * @return void
     */
    protected function checkViewOrSetDefault()
    {
        if (!$this->arResult['VIEW']) {
            return;
        }

        global $APPLICATION;

        $request = Application::getInstance()->getContext()->getRequest();
        $currentView = '';

        if ($request->getQuery('view')) {
            $currentView = $request->getQuery('view') ?: '';
            $_SESSION['view'] = $currentView;
        } elseif ($_SESSION['view']) {
            $currentView = ToLower($_SESSION['view']);
        }

        $isFindView = false;

        foreach ($this->arResult['VIEW'] as $key => $arView) {
            $this->arResult['VIEW'][$key]['URL'] = $APPLICATION->GetCurPageParam('view=' . $arView['TEMPLATE'], array('view'), false);
            if ($arView['TEMPLATE'] == $currentView) {
                $isFindView = true;
                $this->arResult['VIEW'][$key]['ACTIVE'] = 'Y';
            }
        }

        if ($isFindView === false) {
            $this->arResult['VIEW'][0]['ACTIVE'] = 'Y';
        }
    }


    /**
     * @return void
     */
    protected function checkCountOrSetDefault()
    {
        if (!$this->arResult['COUNT']) {
            return;
        }

        global $APPLICATION;

        $request = Application::getInstance()->getContext()->getRequest();
        $currentCount = '';

        if ($request->getQuery('count')) {
            $currentCount = $request->getQuery('count') ?: '';
            $_SESSION['count'] = $currentCount;
        } elseif ($_SESSION['count']) {
            $currentCount = $_SESSION['count'];
        }

        $isFind = false;

        foreach ($this->arResult['COUNT'] as $key => $arCount) {
            $this->arResult['COUNT'][$key]['URL'] = $APPLICATION->GetCurPageParam('count=' . $arCount['VALUE'], array('count'), false);
            if ($arCount['VALUE'] == $currentCount) {
                $isFind = true;
                $this->arResult['COUNT'][$key]['ACTIVE'] = 'Y';
            }
        }

        if ($isFind === false) {
            $this->arResult['COUNT'][0]['ACTIVE'] = 'Y';
        }
    }


    /**
     * @return string
     */
    protected function getActiveView()
    {
        $activeView = '';
        foreach ($this->arResult['VIEW'] as $arView) {
            if ($arView['ACTIVE'] == 'Y') {
                $activeView = $arView['TEMPLATE'];
            }
        }

        return $activeView;
    }

    /**
     * @return array
     */
    protected function getActiveSort()
    {
        $result = array();
        foreach ($this->arResult['SORT'] as $arSort) {
            if ($arSort['ACTIVE'] != 'Y') {
                continue;
            }

            $result = array(
                'FIELD' => $arSort['FIELD'],
                'ORDER' => $arSort['ORDER'],
            );
        }

        return $result;
    }


    /**
     * @return string
     */
    protected function getActiveCount()
    {
        $activeCount = '';
        foreach ($this->arResult['COUNT'] as $arCount) {
            if ($arCount['ACTIVE'] == 'Y') {
                $activeCount = $arCount['VALUE'];
            }
        }

        return $activeCount;
    }

    protected function fillElementsCount()
    {
        $cElement = new \CIBlockElement();
        if (!$this->arParams['FILTER_NAME'] || !$this->arParams['IBLOCK_ID']) {
            return;
        }
        $filterName = $this->arParams['FILTER_NAME'];
        global ${$filterName};
        $arFilterCount = ${$filterName};
        if ($this->arParams['IBLOCK_ID']) {
            $arFilterCount['IBLOCK_ID'] = $this->arParams['IBLOCK_ID'];
        }
        if ($this->arParams['SECTION_ID']) {
            $arFilterCount['SECTION_ID'] = $this->arParams['SECTION_ID'];
        }
        $arFilterCount['INCLUDE_SUBSECTIONS'] = 'Y';
        $arFilterCount['ACTIVE'] = 'Y';

        $this->arResult['ITEMS_COUNT'] = $cElement->GetList(array(), $arFilterCount, array());
    }


    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        $this->fillElementsCount();
        $this->arResult['SORT'] = $this->arParams['SORT'];
        $this->arResult['COUNT'] = $this->arParams['COUNT'];
        $this->arResult['VIEW'] = ($this->arParams['NO_VIEW'] == 'Y') ? array() : $this->arParams['VIEW'];

        if (!empty($this->arResult['SORT'])) {
            $this->checkSortOrSetDefault();
        }

        $this->checkCountOrSetDefault();
        $this->checkViewOrSetDefault();

        $this->includeComponentTemplate();

        return array(
            'SORT' => $this->getActiveSort(),
            'COUNT' => $this->getActiveCount(),
            'TEMPLATE' => $this->getActiveView(),
            'ITEMS_COUNT' => $this->arResult['ITEMS_COUNT'],
        );
    }

}
