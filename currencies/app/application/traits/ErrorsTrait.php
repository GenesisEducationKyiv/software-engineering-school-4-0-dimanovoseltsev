<?php

namespace app\application\traits;

trait ErrorsTrait
{
    /** @var array<string, mixed> */
    protected array $errors = [];

    /**
     * @return array<string, mixed>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $field
     * @param string $message
     * @return void
     */
    protected function addError(string $field, string $message): void
    {
        $this->errors[$field] = (array)($this->errors[$field] ?? []);
        $this->errors[$field][] = $message;
    }

    /**
     * @return void
     */
    protected function clearErrors(): void
    {
        $this->errors = [];
    }

    /**
     * @return bool
     */
    protected function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
