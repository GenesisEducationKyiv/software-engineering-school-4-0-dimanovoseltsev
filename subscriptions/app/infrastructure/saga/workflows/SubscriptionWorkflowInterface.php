<?php

namespace app\infrastructure\saga\workflows;

use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
interface SubscriptionWorkflowInterface
{
    #[WorkflowMethod(name: "SubscriptionWorkflow")]
    public function subscribe(string $email): \Iterator;
}
