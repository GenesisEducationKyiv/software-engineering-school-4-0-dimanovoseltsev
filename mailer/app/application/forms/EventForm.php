<?php

namespace app\application\forms;

use app\application\interfaces\Errorable;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class EventForm extends BaseForm implements FormInterface, Errorable
{
    /**
     * @param ?string $eventId
     * @param ?string $eventType
     * @param ?int $timestamp
     * @param ?array $data
     */
    public function __construct(
        private $eventId,
        private $eventType,
        private $timestamp = null,
        private $data = null,
    ) {
        $this->filterAttributes();
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->clearErrors();

        try {
            $this->validateRequired($this->eventId);
            Assert::string($this->eventId);
        } catch (InvalidArgumentException $e) {
            $this->addError('eventId', $e->getMessage());
        }

        try {
            $this->validateRequired($this->eventType);
            Assert::string($this->eventType);
        } catch (InvalidArgumentException $e) {
            $this->addError('eventType', $e->getMessage());
        }

        try {
            Assert::isArray($this->data);
            Assert::notEmpty($this->data);
        } catch (InvalidArgumentException $e) {
            $this->addError('data', $e->getMessage());
        }

        try {
            $this->validateRequired($this->timestamp);
            $this->validateIdInt((int)$this->timestamp);
        } catch (InvalidArgumentException $e) {
            $this->addError('timestamp', $e->getMessage());
        }

        return !$this->hasErrors();
    }

    /**
     * @return string
     */
    public function getEventId(): string
    {
        return (string)$this->eventId;
    }

    /**
     * @return string
     */
    public function getEventType(): string
    {
        return (string)$this->eventType;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return (int)$this->timestamp;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return (array)$this->data;
    }
}
