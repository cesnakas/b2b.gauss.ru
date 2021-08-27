<?php
use Bitrix\Main\Application;

$requestData = Application::getInstance()->getContext()->getRequest()->toArray();
$dir = $APPLICATION->GetCurDir();

$pathList = [
    ['URL' => '/personal/orders/',
        'GET' => '',
        'GET_2' => '',
        'ANCHOR' => 'В работе'],

    ['URL' => '/personal/orders/?filter_history=Y',
        'GET' => 'filter_history',
        'GET_2' => '',
        'ANCHOR' => 'Выполненные'],

    ['URL' => '/personal/orders/?filter_history=Y&show_canceled=Y',
        'GET' => 'filter_history',
        'GET_2' => 'show_canceled',
        'ANCHOR' => 'Отмененные'],
]; ?>
<div class="b-tabs-head">
    <? foreach ($pathList as $path) {

        $active = '';

        if (empty($path['GET']) && !array_key_exists('filter_history', $requestData) &&
            !array_key_exists('show_canceled', $requestData)
        ) {
            $active = ($dir == $path['URL']) ? 'active' : '';
        } elseif (empty($path['GET_2']) && array_key_exists('filter_history', $requestData) &&
            !array_key_exists('show_canceled', $requestData)
        ) {
            $active = array_key_exists($path['GET'], $requestData) ? 'active' : '';
        } else {
            $active = (array_key_exists($path['GET'], $requestData) && array_key_exists($path['GET_2'], $requestData)) ? 'active' : '';
        } ?>
        <a href="<?= $path['URL']; ?>"
           class="b-tabs-link <?= $active ?>"><?= $path['ANCHOR']; ?></a>
    <? } ?>
</div>