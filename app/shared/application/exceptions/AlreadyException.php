<?php

namespace app\shared\application\exceptions;

use Exception;
use Throwable;

class AlreadyException extends Exception implements Throwable
{
    /**
     * ConflictException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Already", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
