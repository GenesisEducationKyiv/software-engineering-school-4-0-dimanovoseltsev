<?php

namespace app\infrastructure\services;

use app\application\exceptions\InvalidCallException;
use app\application\interfaces\ActivityServiceInterface;
use Temporal\Client\WorkflowClient;
use Temporal\DataConverter\Type;
use Temporal\Internal\Client\WorkflowProxy;
use Throwable;

class TemporalActivityService implements ActivityServiceInterface
{
    /**
     * @param WorkflowProxy<WorkflowClient> $subscribeWorkflow
     */
    public function __construct(
        private readonly WorkflowProxy $subscribeWorkflow,
    ) {
    }

    /**
     * @param string $email
     * @return array<string,mixed>
     * @throws InvalidCallException
     */
    public function subscribe(string $email): array
    {
        try {
            $process = $this->subscribeWorkflow->client->start($this->subscribeWorkflow, $email);
            return (array)$process->getResult(Type::TYPE_ARRAY);
        } catch (Throwable $e) {
            throw new InvalidCallException($e->getMessage(), previous: $e);
        }
    }
}
