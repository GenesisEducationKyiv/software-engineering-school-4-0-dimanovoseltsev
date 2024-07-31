<?php

namespace app\application\actions;

use app\application\exceptions\AlreadyException;
use app\application\exceptions\InvalidCallException;
use app\application\exceptions\NotValidException;
use app\application\forms\SubscribeForm;
use app\application\interfaces\ActivityServiceInterface;
use app\application\mappers\Mapper;
use app\application\services\LogServiceInterface;
use app\application\services\SubscriptionServiceInterface;
use app\domain\entities\Subscription;
use Throwable;

class Subscribe extends BaseAction implements SubscribeInterface
{
    /**
     * @param SubscriptionServiceInterface $service
     * @param ActivityServiceInterface $activityService
     * @param LogServiceInterface $logService
     */
    public function __construct(
        private readonly SubscriptionServiceInterface $service,
        private readonly ActivityServiceInterface $activityService,
        private readonly LogServiceInterface $logService,
    ) {
    }

    /**
     * @param SubscribeForm $form
     * @return Subscription
     * @throws AlreadyException
     * @throws InvalidCallException
     * @throws NotValidException
     * @throws Throwable
     */
    public function execute(SubscribeForm $form): Subscription
    {
        if (!$form->validate()) {
            throw new NotValidException($form->getErrors());
        }

        $entity = $this->service->getByEmail($form->getEmail());
        if ($entity !== null) {
            throw new AlreadyException("Already subscribed");
        }

        try {
            $transactionResult = $this->activityService->subscribe($form->getEmail());
        } catch (InvalidCallException $e) {
            $this->logService->debug(sprintf("Error in subscribes %s", $e->getMessage()));
            throw new InvalidCallException(previous: $e);
        } catch (Throwable $e) {
            throw $e;
        }

        return Mapper::fromPrimitive($transactionResult['subscription']);
    }
}
