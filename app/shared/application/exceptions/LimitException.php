<?php

namespace app\shared\application\exceptions;

use Exception;
use Throwable;

class LimitException extends Exception implements Throwable
{
    /**
     * InvalidDatabaseException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Limit", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
