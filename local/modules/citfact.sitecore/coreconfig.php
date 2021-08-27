<?php


namespace Citfact\SiteCore;


class CoreConfig
{
    const NAME = 'CitFact';
    const ERROR_LOG = '/local/var/log/' . self::NAME . '.error.log';
    const MAIN_LOG =  '/local/var/log/' . self::NAME . '.main.log';
}