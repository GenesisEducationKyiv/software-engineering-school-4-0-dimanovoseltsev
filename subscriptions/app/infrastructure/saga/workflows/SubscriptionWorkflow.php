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
use Yii;

/**
 * @property ActivityProxy|SubscriptionActivitiesInterface $activities
 */
class SubscriptionWorkflow implements SubscriptionWorkflowInterface
{
    private ActivityProxy $activities;

    public function __construct()
    {
        $this->activities = Workflow::newActivityStub(
            SubscriptionActivitiesInterface::class,
            ActivityOptions::new()
                ->withStartToCloseTimeout(CarbonInterval::minute(1))
                ->withRetryOptions(RetryOptions::new()->withMaximumAttempts(2))
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

        /** @var SubscriptionActivitiesInterface $activities */
        $activities = $this->activities;
        try {
            $subscription = yield $activities->createSubscription($email);
            $saga->addCompensation(fn() => yield $activities->cancelSubscription($email));

            $customer = yield $activities->createCustomer($email);
            $saga->addCompensation(fn() => yield $activities->deleteCustomer($email));

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
