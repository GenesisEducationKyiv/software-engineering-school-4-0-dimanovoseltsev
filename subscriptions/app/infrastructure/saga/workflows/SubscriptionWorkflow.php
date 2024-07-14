<?php

namespace app\infrastructure\saga\workflows;

use app\infrastructure\saga\activities\SubscriptionActivitiesInterface;
use Carbon\CarbonInterval;
use Iterator;
use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;
use Temporal\Workflow;
use Throwable;
use Yii;

class SubscriptionWorkflow implements SubscriptionWorkflowInterface
{
    private SubscriptionActivitiesInterface $activities;

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
            $subscription = yield $this->activities->createSubscription($email);
            $saga->addCompensation(fn() => yield $this->activities->cancelSubscription($email));

            $customer = yield $this->activities->createCustomer($email);
            $saga->addCompensation(fn() => yield $this->activities->deleteCustomer($email));

            return [
                'subscription' => $subscription,
                'customer' => $customer,
            ];
        } catch (Throwable $e) {
            Yii::error("Subscription {$email} failed: " . $e->getMessage());
            yield $saga->compensate();
            throw $e;
        }
    }
}
