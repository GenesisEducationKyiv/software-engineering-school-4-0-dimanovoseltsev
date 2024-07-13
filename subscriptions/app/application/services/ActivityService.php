<?php

namespace app\application\services;

use app\domain\entities\Customer;
use app\domain\repositories\CustomerRepositoryInterface;

class TemporalActivityService implements CustomerServiceInterface
{
    public function __construct(
        private \Temporal\Client\WorkflowClient $client,
        private \Temporal\Internal\Client\WorkflowProxy $workflow,
    )
    {

    }


    public function subscribe(string $email): array
    {
        return [];
    }
}
