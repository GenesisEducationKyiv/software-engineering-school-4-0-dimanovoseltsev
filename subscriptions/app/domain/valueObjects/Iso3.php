<?php

namespace app\domain\valueObjects;

use Webmozart\Assert\Assert;

final class Iso3 implements ValueObjectInterface
{
    /**
     * @param string $value
     */
    public function __construct(private readonly string $value)
    {
        Assert::string($value);
        Assert::length($value, 3);
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }
}
