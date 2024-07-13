<?php

namespace app\infrastructure\saga\activities;


use app\application\actions\CreateCustomerInterface;
use app\application\actions\CreateSubscriptionInterface;
use app\application\actions\DeleteCustomerInterface;
use app\application\actions\UnsubscribeInterface;
use app\application\exceptions\AlreadyException;
use app\application\exceptions\NotValidException;
use app\application\forms\CreateCustomerForm;
use app\application\forms\DeleteCustomerForm;
use app\application\forms\SaveCustomerForm;
use app\application\forms\UnsubscribeForm;
use Temporal\Activity\ActivityMethod;

class SubscriptionActivities implements SubscriptionActivitiesInterface
{
    /**
     * @param CreateSubscriptionInterface $createSubscription
     * @param UnsubscribeInterface $unsubscribe
     * @param CreateCustomerInterface $createCustomer
     * @param DeleteCustomerInterface $deleteCustomer
     */
    public function __construct(
        private readonly CreateSubscriptionInterface $createSubscription,
        private readonly UnsubscribeInterface $unsubscribe,
        private readonly CreateCustomerInterface $createCustomer,
        private readonly DeleteCustomerInterface $deleteCustomer,
    ) {
    }

    /**
     * @param string $email
     * @return array{id: int, email: string}
     * @throws AlreadyException
     * @throws NotValidException
     */
    #[ActivityMethod] public function saveSubscription(string $email): array
    {
        return $this->createSubscription->execute(new CreateCustomerForm($email))->toArray();
    }

    /**
     * @param string $email
     * @return bool
     */
    #[ActivityMethod] public function cancelSubscription(string $email): bool
    {
        return $this->unsubscribe->execute(new UnsubscribeForm($email));
    }

    /**
     * @param string $email
     * @return array{id: int, email: string}
     * @throws NotValidException
     */
    #[ActivityMethod] public function saveCustomer(string $email): array
    {
        return $this->createCustomer->execute(new SaveCustomerForm($email))->toArray();
    }

    /**
     * @param string $email
     * @return bool
     * @throws NotValidException
     */
    #[ActivityMethod] public function cancelCustomer(string $email): bool
    {
        return $this->deleteCustomer->execute(new DeleteCustomerForm($email));
    }
}
