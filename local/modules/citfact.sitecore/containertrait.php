<?php


namespace Citfact\SiteCore;


trait ContainerTrait
{
    public static function getContainer()
    {
        return $container = Container::getInstance();
    }

}