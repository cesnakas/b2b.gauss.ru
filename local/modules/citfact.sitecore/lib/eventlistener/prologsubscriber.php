<?php

namespace Citfact\SiteCore\EventListener;

use Bitrix\Main\Application;
use Citfact\Tools\Event\SubscriberInterface;
use Bitrix\Main\Localization\Loc;
use Citfact\Sitecore\Ajax;

Loc::loadMessages(__FILE__);

class PrologSubscriber implements SubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ['module' => 'main', 'event' => 'OnBeforeProlog', 'method' => 'handleAjax'],
            ['module' => 'main', 'event' => 'OnEpilog', 'method' => '_Check404Error'],
        ];
    }


    public static function handleAjax()
    {
        // Обработка AJAX-запросов
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $app = Application::getInstance();
            $request = $app->getContext()->getRequest();
            if ($request->getPost('isAjaxAction') == 'Y') {
                $ajaxDispatcher = new Ajax\Dispatcher();
                die();
            }
        }
    }

    function _Check404Error(){
        if (defined('ERROR_404') && ERROR_404 == 'Y' && ERROR_404_PAGE !== 'Y') {
            global $APPLICATION;
            $APPLICATION->RestartBuffer();
            include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/header.php';
            include $_SERVER['DOCUMENT_ROOT'] . '/404.php';
            include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/footer.php';
        }
    }
}