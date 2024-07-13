<?php

namespace app\infrastructure\saga\workflows;

use app\infrastructure\saga\activities\SubscriptionActivitiesInterface;
use Carbon\CarbonInterval;
use Iterator;
use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;
use Temporal\Internal\Workflow\ActivityProxy;
use Temporal\Workflow;
use Throwable;

class SubscriptionWorkflow implements SubscriptionWorkflowInterface
{
    private ActivityProxy|SubscriptionActivitiesInterface $activities;

    public function __construct()
    {
        $this->activities = Workflow::newActivityStub(
            SubscriptionActivitiesInterface::class,
            ActivityOptions::new()
                ->withStartToCloseTimeout(CarbonInterval::minute(1))
                // disable retries for example to run faster
                ->withRetryOptions(RetryOptions::new()->withMaximumAttempts(1))
        );
    }

    /**
     * @param string $email
     * @return Iterator
     * @throws Throwable
     */
    public function subscribe(string $email): Iterator
    {
        $saga = new Workflow\Saga();

        // Configure SAGA to run compensation activities in parallel
        $saga->setParallelCompensation(true);

        try {
            $subscription = yield $this->activities->saveSubscription($email);
            $saga->addCompensation(fn() => yield $this->activities->cancelSubscription($email));

            $customer = yield $this->activities->saveCustomer($email);
            $saga->addCompensation(fn() => yield $this->activities->cancelCustomer($email));

            return [
                'subscription' => $subscription,
                'customer' => $customer,
            ];
        } catch (Throwable $e) {
            Workflow::log("Subscription {$email} failed: " . $e->getMessage());
            yield $saga->compensate();
            throw $e;
        }
    }
}
