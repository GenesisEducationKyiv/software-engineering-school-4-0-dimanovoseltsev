<?php

namespace app\application\forms;

use app\application\interfaces\Errorable;
use app\application\traits\TimestampTrait;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class MailCreateEventForm extends BaseForm implements FormInterface, Errorable
{
    use TimestampTrait;

    /**
     * @param ?string $email
     * @param ?string $currencyIso3
     * @param ?float $currencyRate
     * @param ?int $currencyUpdatedAt
     */
    public function __construct(
        private $email = null,
        private $currencyIso3 = null,
        private $currencyRate = null,
        private $currencyUpdatedAt = null,
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
            $this->validateRequired($this->email);
            Assert::string($this->email);
            $this->validateEmail($this->email);
        } catch (InvalidArgumentException $e) {
            $this->addError('email', $e->getMessage());
        }

        try {
            $this->validateRequired($this->currencyIso3);
            Assert::string($this->currencyIso3);
        } catch (InvalidArgumentException $e) {
            $this->addError('iso3', $e->getMessage());
        }

        try {
            $this->validateRequired($this->currencyRate);
            Assert::float($this->currencyRate);
        } catch (InvalidArgumentException $e) {
            $this->addError('rate', $e->getMessage());
        }

        try {
            $this->validateRequired($this->currencyUpdatedAt);
            $this->validateIdInt((int)$this->currencyUpdatedAt);
        } catch (InvalidArgumentException $e) {
            $this->addError('updatedAt', $e->getMessage());
        }

        return !$this->hasErrors();
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string)$this->email;
    }

    /**
     * @return string
     */
    public function getCurrencyIso3(): string
    {
        return (string)$this->currencyIso3;
    }

    /**
     * @return float
     */
    public function getCurrencyRate(): float
    {
        return (float)$this->currencyRate;
    }

    /**
     * @return int
     */
    public function getCurrencyUpdatedAt(): int
    {
        return (int)$this->currencyUpdatedAt;
    }
}
