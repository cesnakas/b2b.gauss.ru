<?php


namespace Citfact\SiteCore;

use League\Container\Container as BaseContainer;
use League\Container\ContainerInterface;


class Container extends BaseContainer
{
    /**
     * @var ContainerInterface
     */
    private static $instance = null;

    /**
     * Returns current instance of the Container.
     *
     * @return ContainerInterface
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        static::$instance = new static();

        return static::$instance;
    }

    public static function test()
    {
        echo 'Container is work';
    }

    public static function find($name)
    {
        return self::getInstance()->get($name);
    }
}
