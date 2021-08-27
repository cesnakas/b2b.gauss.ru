<?php


namespace Citfact\SiteCore\Tools;


class ModalManager
{
    protected $timeSec = 10*60 ;
    const NAME_SESSION = 'modalFact';


    /**
     * @param null $timeSec здесь задается количество секунд жизни сессии.
     * По умолчанию равно 30 минутам
     */
    public function canOpenModal($timeSec = null)
    {
        session_start();
        $result = true;
        if(is_null($timeSec)) {
            $timeSec = $this->timeSec;
        }
        if (!isset($_SESSION[static::NAME_SESSION]['time'])) {
            $_SESSION[static::NAME_SESSION]['time'] = time();
            $result = true;
        } else if ($_SESSION[static::NAME_SESSION]['time'] + $timeSec < time()) {
            $_SESSION[static::NAME_SESSION]['time'] = time();
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }
}