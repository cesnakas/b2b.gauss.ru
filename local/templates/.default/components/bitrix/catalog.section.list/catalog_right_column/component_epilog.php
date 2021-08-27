<?php

use Citfact\Tools\ElementManager;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

ElementManager::setIpropValues($arResult['SECTION']['IPROPERTY_VALUES'], $arResult['SECTION']['~NAME'], 'SECTION');
