<?php

namespace app\infrastructure\saga\activities;

use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[ActivityInterface(prefix: "SubscriptionActivities.")]
interface SubscriptionActivitiesInterface
{
    /**
     * @param string $email
     * @return array<string, mixed>
     */
    #[ActivityMethod]
    public function createSubscription(string $email): array;

    /**
     * @param string $email
     * @return bool
     */
    #[ActivityMethod]
    public function cancelSubscription(string $email): bool;

    /**
     * @param string $email
     * @return array<string, mixed>
     */
    #[ActivityMethod]
    public function createCustomer(string $email): array;

    /**
     * @param string $email
     * @return bool
     */
    #[ActivityMethod]
    public function deleteCustomer(string $email): bool;
}
