<?php

namespace app\shared\application\exceptions;

use Exception;
use Throwable;

class ForbiddenException extends Exception implements Throwable
{
    /**
     * ForbiddenException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Forbidden", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
