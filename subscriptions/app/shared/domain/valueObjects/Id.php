<?php

namespace app\shared\domain\valueObjects;

use app\application\traits\ValidationRulesTrait;
use app\domain\valueObjects\ValueObjectInterface;
use Webmozart\Assert\Assert;

final class Id implements ValueObjectInterface
{
    use ValidationRulesTrait;

    /**
     * @param int|null $value
     */
    public function __construct(private readonly ?int $value)
    {
        Assert::nullOrInteger($this->value);
        if ($this->value !== null) {
            $this->validateIdInt($this->value);
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
