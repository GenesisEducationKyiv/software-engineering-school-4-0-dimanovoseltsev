<?php

namespace app\shared\application\exceptions;

use Exception;
use Throwable;

class BadRequestException extends Exception implements Throwable
{
    /**
     * ConflictException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Bad Request", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
