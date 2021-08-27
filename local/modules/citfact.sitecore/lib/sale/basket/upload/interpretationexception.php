<?php

namespace Citfact\SiteCore\Sale\Basket\Upload;


use Exception;

class InterpretationException extends \Exception
{

    /**
     * InterpretationException constructor.
     * @param $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}