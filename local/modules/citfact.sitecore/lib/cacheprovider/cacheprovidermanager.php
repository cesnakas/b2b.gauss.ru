<?php

namespace Citfact\SiteCore\CacheProvider;


class CacheProviderManager extends \Bitrix\Main\Data\StaticCacheProvider
{
    public static function createKey()
    {
        $params = array(
            'view' => array(
                'DEFAULT' => 'table',
            ),
            'auth' => array(
                'DEFAULT' => 'N',
            ),
            'price' => array(
                'DEFAULT' => \Citfact\Sitecore\CatalogHelper\Price::PRICE_ID_MAIN,
            )
        );

        $pageName = 'page';

        $isBot = (
            strpos($_SERVER['HTTP_USER_AGENT'], 'YandexBot') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'YandexScreenshotBot') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Googlebot') !== false
        ) ? 'Y' : 'N';

        foreach ($params as $code => $info) {
            if (!empty($_GET[$code]) && !$info['SKIP_GET']) {
                $pageName .= '_' . $code . '_' . $_GET[$code];
            } elseif (!empty($_SESSION[$code])) {
                $pageName .= '_' . $code . '_' . $_SESSION[$code];
            } else {
                $pageName .= '_' . $code . '_' . $info['DEFAULT'];
            }
        }

        $pageName .= '_isBot_' . $isBot;

        return $pageName;
    }

    public function setUserPrivateKey()
    {
    }

    public function isCacheable()
    {
        return true;
    }

    public function getCachePrivateKey()
    {
        return self::createKey();
    }

    public function onBeforeEndBufferContent()
    {
    }
}