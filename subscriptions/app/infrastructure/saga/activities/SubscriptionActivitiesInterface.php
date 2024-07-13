<?php

namespace app\infrastructure\saga\activities;

use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;


#[ActivityInterface(prefix: "SubscriptionActivities.")]
interface SubscriptionActivitiesInterface
{
    #[ActivityMethod]
    public function saveSubscription(string $email): array;

    #[ActivityMethod]
    public function cancelSubscription(string $email): bool;

    #[ActivityMethod]
    public function saveCustomer(string $email): array;

    #[ActivityMethod]
    public function cancelCustomer(string $email): bool;
}
