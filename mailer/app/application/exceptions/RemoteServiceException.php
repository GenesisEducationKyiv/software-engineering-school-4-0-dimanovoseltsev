<?php

namespace app\application\exceptions;

use Exception;
use Throwable;

/**
 * Class RemoteServiceException.
 *
 */
class RemoteServiceException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 503, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
