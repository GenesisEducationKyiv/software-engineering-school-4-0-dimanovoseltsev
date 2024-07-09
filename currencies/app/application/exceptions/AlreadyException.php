<?php

namespace app\application\exceptions;

use Exception;
use Throwable;

class AlreadyException extends Exception
{
    /**
     * ConflictException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Already", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
