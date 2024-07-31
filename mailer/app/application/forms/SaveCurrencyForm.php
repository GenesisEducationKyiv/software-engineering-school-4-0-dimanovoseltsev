<?php

namespace app\application\forms;

use app\application\interfaces\Errorable;
use app\application\traits\TimestampTrait;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class SaveCurrencyForm extends BaseForm implements FormInterface, Errorable
{
    use TimestampTrait;

    /**
     * @param ?string $iso3
     * @param ?float $rate
     * @param ?int $updatedAt
     */
    public function __construct(
        private $iso3 = null,
        private $rate = null,
        private $updatedAt = null,
    ) {
        $this->setCurrentTimestamp();
        $this->filterAttributes();
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->clearErrors();

        try {
            $this->validateRequired($this->iso3);
            Assert::string($this->iso3);
        } catch (InvalidArgumentException $e) {
            $this->addError('iso3', $e->getMessage());
        }

        try {
            $this->validateRequired($this->rate);
            Assert::float($this->rate);
        } catch (InvalidArgumentException $e) {
            $this->addError('rate', $e->getMessage());
        }

        try {
            $this->validateRequired($this->updatedAt);
            $this->validateIdInt((int)$this->updatedAt);
        } catch (InvalidArgumentException $e) {
            $this->addError('updatedAt', $e->getMessage());
        }

        return !$this->hasErrors();
    }


    /**
     * @return string
     */
    public function getIso3(): string
    {
        return (string)$this->iso3;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return (float)$this->rate;
    }

    /**
     * @return int
     */
    public function getUpdatedAt(): int
    {
        return (int)$this->updatedAt;
    }
}
