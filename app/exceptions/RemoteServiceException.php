<?php

namespace app\exceptions;

use Exception;

/**
 * Class RemoteServiceException.
 *
 * @package app\exceptions
 */
class RemoteServiceException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(string $message = "", int $code = 503, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
