<?php

namespace app\exceptions;

/**
 * Class RemoteServiceException.
 *
 * @package app\exceptions
 */
class RemoteServiceException extends \Exception
{
    /**
     * @inheritdoc
     */
    public function __construct($message = "", $code = 503, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
