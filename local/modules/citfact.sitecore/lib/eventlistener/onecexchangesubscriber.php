<?php

namespace Citfact\SiteCore\EventListener;

use Bitrix\Main\Diag\Debug;
use Citfact\Tools\Event\SubscriberInterface;

class OneCExchangeSubscriber implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
//            ['module' => 'catalog', 'event' => 'OnBeforeCatalogImportHL', 'sort' => 100, 'method' => 'OnBeforeCatalogImport'],
//            ['module' => 'catalog', 'event' => 'OnBeforeCatalogImport1C', 'sort' => 100, 'method' => 'OnBeforeCatalogImport'],
        ];
    }

    public static function OnBeforeCatalogImport($arParams, $ABS_FILE_NAME)
    {
        if (file_exists($ABS_FILE_NAME) == false /*|| !isDev()*/) {
            return;
        }

        $fileInfo = pathinfo($ABS_FILE_NAME);

        $newFileName = $_SERVER['DOCUMENT_ROOT'] . '/local/var/tmp1c/' . time() . '_' . $fileInfo['filename'] . '.' . $fileInfo['extension'];

        mkdir($_SERVER['DOCUMENT_ROOT'] . '/local/var/tmp1c/', 0775, true); // создаем директорию если ее нет, т.к. file_put_contents не делает этого

        if (file_exists($newFileName)) {
            unlink($newFileName);
        }

        copy($ABS_FILE_NAME, $newFileName);
    }
}