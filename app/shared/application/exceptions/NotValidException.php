<?php

namespace app\shared\application\exceptions;

use Exception;
use Throwable;

class NotValidException extends Exception implements Throwable
{
    /**
     * @param array<int|string, mixed> $errors
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        private readonly array $errors,
        string $message = "Validation Failed",
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array<int|string, mixed>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function getErrorsAsResponse(): array
    {
        $items = [];

        foreach ($this->errors as $field => $values) {
            if (!isset($values[0])) {
                continue;
            }

            $items[] = ['field' => $field, 'message' => $values[0]];
        }

        return $items;
    }
}
