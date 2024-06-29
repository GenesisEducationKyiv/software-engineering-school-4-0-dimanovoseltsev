<?php

namespace app\application\exceptions;

use Exception;
use Throwable;

/**
 * Class UnexpectedValueException.
 */
class UnexpectedValueException extends Exception implements Throwable
{
    /**
     * UnexpectedValueException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Unexpected value", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
