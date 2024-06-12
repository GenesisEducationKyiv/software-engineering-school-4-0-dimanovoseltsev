<?php

namespace app\currencies\domain\valueObjects;

use app\shared\domain\valueObjects\ValueObjectInterface;
use Webmozart\Assert\Assert;

final class Iso3 implements ValueObjectInterface
{
    /**
     * @param string|null $value
     */
    public function __construct(private readonly ?string $value)
    {
        Assert::string($value);
        Assert::length($value, 3);
    }

    /**
     * @return mixed|int
     */
    public function value(): mixed
    {
        return $this->value;
    }
}
