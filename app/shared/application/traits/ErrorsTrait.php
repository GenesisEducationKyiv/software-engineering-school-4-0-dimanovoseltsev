<?php

namespace app\shared\application\traits;

trait ErrorsTrait
{
    /** @var array<string, mixed>  */
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
        $this->errors[$field] = $this->errors[$field] ?? [];
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

    /**
     * @param array<string, mixed> $errors
     * @return void
     */
    protected function mergeErrors(array $errors): void
    {
        foreach ($errors as $field => $values) {
            foreach ($values as $value) {
                $this->addError($field, $value);
            }
        }
    }
}
