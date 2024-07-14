<?php

namespace tests\unit\app\infrastructure\saga\activities;

use app\application\actions\CreateCustomer;
use app\application\actions\CreateSubscription;
use app\application\actions\DeleteCustomer;
use app\application\actions\Unsubscribe;
use app\application\exceptions\AlreadyException;
use app\application\exceptions\NotValidException;
use app\application\forms\CreateCustomerForm;
use app\application\forms\DeleteCustomerForm;
use app\application\forms\SubscribeForm;
use app\application\forms\UnsubscribeForm;
use app\infrastructure\saga\activities\SubscriptionActivities;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class SubscriptionActivitiesTest extends UnitTestCase
{
    private SubscriptionActivities $activity;
    private CreateSubscription|MockObject $createSubscription;
    private Unsubscribe|MockObject $unsubscribe;
    private CreateCustomer|MockObject $createCustomer;
    private DeleteCustomer|MockObject $deleteCustomer;

    public function setUp(): void
    {
        parent::setUp();
        $this->createSubscription = $this->getActionMock(CreateSubscription::class);
        $this->unsubscribe = $this->getActionMock(Unsubscribe::class);
        $this->createCustomer = $this->getActionMock(CreateCustomer::class);
        $this->deleteCustomer = $this->getActionMock(DeleteCustomer::class);
        $this->activity = new SubscriptionActivities(
            $this->createSubscription,
            $this->unsubscribe,
            $this->createCustomer,
            $this->deleteCustomer,
        );
    }


    /**
     * @throws NotValidException
     * @throws AlreadyException
     */
    public function testSaveSubscription()
    {
        $entity = $this->getSubscriptionEntity();
        $email = "mai@mail.com";

        $this->createSubscription->expects(self::once())
            ->method('execute')
            ->with(new SubscribeForm($email))
            ->willReturn($entity);

        $actual = $this->activity->createSubscription($email);
        self::assertIsArray($actual);
    }

    public function testCancelSubscription()
    {
        $email = "mai@mail.com";

        $this->unsubscribe->expects(self::once())
            ->method('execute')
            ->with(new UnsubscribeForm($email))
            ->willReturn(true);

        $actual = $this->activity->cancelSubscription($email);
        self::assertTrue($actual);
    }

    /**
     * @throws NotValidException
     * @throws AlreadyException
     */
    public function testSaveCustomer()
    {
        $entity = $this->getCustomerEntity();
        $email = "mai@mail.com";

        $this->createCustomer->expects(self::once())
            ->method('execute')
            ->with(new CreateCustomerForm($email))
            ->willReturn($entity);

        $actual = $this->activity->createCustomer($email);
        self::assertIsArray($actual);
    }

    /**
     * @throws NotValidException
     */
    public function testCancelCustomer()
    {
        $email = "mai@mail.com";

        $this->deleteCustomer->expects(self::once())
            ->method('execute')
            ->with(new DeleteCustomerForm($email))
            ->willReturn(true);

        $actual = $this->activity->deleteCustomer($email);
        self::assertTrue($actual);
    }
}

