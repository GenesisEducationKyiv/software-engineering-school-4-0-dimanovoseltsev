<?php

namespace app\domain\valueObjects;

use app\application\traits\ValidationRulesTrait;

final class Email implements ValueObjectInterface
{
    use ValidationRulesTrait;

    /**
     * @param string $value
     */
    public function __construct(private readonly string $value)
    {
        $this->validateEmail($this->value);
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }
}
