<?php

namespace Citfact\SiteCore\CacheProvider;

class LkCacheManager
{
    /**
     * @param string|array $params
     * @return string
     */
    public static function getCacheId($params)
    {
        return 'LkCacheManager' . $params;
    }

    public static function getCacheTime()
    {
        return 3600;
    }

    public static function getTag()
    {
        return 'LkCacheManager';
    }

    public static function getCacheIdForPdz($period, $kontragents, $isdefaultPeriod)
    {
        $ids = array_column($kontragents, 'ID');
        sort($ids);
        $ids = implode('', $ids);
        return static::getCacheId('getPdz' . $period . $ids . $isdefaultPeriod);
    }

    public static function getCacheIdForDz($period, $kontragents, $isdefaultPeriod)
    {
        $ids = array_column($kontragents, 'ID');
        sort($ids);
        $ids = implode('', $ids);
        return static::getCacheId('getDz' . $period . $ids . $isdefaultPeriod);
    }

    public static function getCacheIdForFacts($period, $kontragents)
    {
        $ids = array_column($kontragents, 'ID');
        sort($ids);
        $ids = implode('', $ids);
        return static::getCacheId('getFacts' . $period . $ids);
    }

    public static function clearCache()
    {
        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag(static::getTag());
    }
}