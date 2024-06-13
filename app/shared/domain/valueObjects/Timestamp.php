<?php

namespace app\shared\domain\valueObjects;

use Webmozart\Assert\Assert;

final class Timestamp implements ValueObjectInterface
{
    /**
     * @param int|null $value
     */
    public function __construct(private readonly ?int $value)
    {
        Assert::nullOrInteger($this->value);
        if ($this->value !== null) {
            Assert::greaterThan($this->value, 0);
        }
    }

    /**
     * @return ?int
     */
    public function value(): ?int
    {
        return $this->value;
    }
}
