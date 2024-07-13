<?php

namespace app\saga\activities;

use app\domain\entities\Subscription;
use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;


#[ActivityInterface(prefix: "SubscriptionActivities.")]
interface SubscriptionActivitiesInterface
{
    #[ActivityMethod]
    public function saveSubscription(string $email): Subscription;

    #[ActivityMethod]
    public function cancelSubscription(string $email);

    #[ActivityMethod]
    public function saveCustomer(string $email);

    #[ActivityMethod]
    public function cancelCustomer(string $email);
}
