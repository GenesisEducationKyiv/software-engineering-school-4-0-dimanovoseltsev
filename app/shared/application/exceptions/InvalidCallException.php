<?php

namespace app\shared\application\exceptions;

use Exception;
use Throwable;

class InvalidCallException extends Exception implements Throwable
{
    /**
     * InvalidCallException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Invalid Call", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
