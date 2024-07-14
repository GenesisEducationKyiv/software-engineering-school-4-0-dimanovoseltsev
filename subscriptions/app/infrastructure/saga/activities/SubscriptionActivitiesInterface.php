<?php

namespace app\infrastructure\saga\activities;

use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[ActivityInterface(prefix: "SubscriptionActivities.")]
interface SubscriptionActivitiesInterface
{
    #[ActivityMethod]
    public function createSubscription(string $email): array;

    #[ActivityMethod]
    public function cancelSubscription(string $email): bool;

    #[ActivityMethod]
    public function createCustomer(string $email): array;

    #[ActivityMethod]
    public function deleteCustomer(string $email): bool;
}
