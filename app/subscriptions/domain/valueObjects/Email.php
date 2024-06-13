<?php

namespace app\subscriptions\domain\valueObjects;

use app\shared\application\traits\ValidationRulesTrait;
use app\shared\domain\valueObjects\ValueObjectInterface;

final class Email implements ValueObjectInterface
{
    use ValidationRulesTrait;

    /**
     * @param string|null $value
     */
    public function __construct(private readonly ?string $value)
    {
        if(is_string($this->value)){
            $this->validateEmail($this->value);
        }
    }

    /**
     * @return ?string
     */
    public function value(): ?string
    {
        return $this->value;
    }
}
