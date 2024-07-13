<?php

namespace app\saga\activities;


use app\application\dto\CreateSubscriptionDto;
use app\application\services\SubscriptionServiceInterface;
use app\domain\entities\Subscription;
use Temporal\Activity\ActivityMethod;

class SubscriptionActivities implements SubscriptionActivitiesInterface
{

    /**
     * @param SubscriptionServiceInterface $service
     */
    public function __construct(
        private readonly SubscriptionServiceInterface $service,
    ) {
    }

    /**
     * @param string $email
     * @return Subscription
     */
    #[ActivityMethod] public function saveSubscription(string $email): Subscription
    {
        return $this->service->create(
            new CreateSubscriptionDto($email, time())
        );
    }

    #[ActivityMethod] public function cancelSubscription(string $email)
    {
        // @todo debug
        echo '<pre>';
        print_r(array(
            'file' => __FILE__,
            'line' => __LINE__,
            '$email' => $email,
        ));
        echo '</pre>';
        die();
        // TODO: Implement cancelSubscription() method.
    }

    #[ActivityMethod] public function saveCustomer(string $email)
    {
        // @todo debug
        echo '<pre>';
        print_r(array(
            'file' => __FILE__,
            'line' => __LINE__,
            '$email' => $email,
        ));
        echo '</pre>';
        die();
        // TODO: Implement saveCustomer() method.
    }

    #[ActivityMethod] public function cancelCustomer(string $email)
    {
        // @todo debug
        echo '<pre>';
        print_r(array(
            'file' => __FILE__,
            'line' => __LINE__,
            '$email' => $email,
        ));
        echo '</pre>';
        die();
        // TODO: Implement cancelCustomer() method.
    }
}
