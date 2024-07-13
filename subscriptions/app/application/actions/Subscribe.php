<?php

namespace app\application\actions;

use app\application\exceptions\AlreadyException;
use app\application\exceptions\NotValidException;
use app\application\forms\SubscribeForm;
use app\application\interfaces\ActivityServiceInterface;
use app\application\mappers\Mapper;
use app\application\services\SubscriptionServiceInterface;
use app\domain\entities\Subscription;

class Subscribe extends BaseAction implements SubscribeInterface
{
    /**
     * @param SubscriptionServiceInterface $service
     * @param ActivityServiceInterface $activityService
     */
    public function __construct(
        private readonly SubscriptionServiceInterface $service,
        private readonly ActivityServiceInterface $activityService,
    ) {
    }

    /**
     * @param SubscribeForm $form
     * @return Subscription
     * @throws AlreadyException
     * @throws NotValidException
     */
    public function execute(SubscribeForm $form): Subscription
    {
        if (!$form->validate() || $form->getEmail() === null) {
            throw new NotValidException($form->getErrors());
        }

        $entity = $this->service->getByEmail($form->getEmail());
        if ($entity !== null) {
            throw new AlreadyException("Already subscribed");
        }

        $transactionResult = $this->activityService->subscribe($form->getEmail());
        return Mapper::fromPrimitive($transactionResult['subscription']);
    }
}
