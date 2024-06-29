<?php

use app\application\services\LogServiceInterface;
use app\infrastructure\services\YiiLogger;
use yii\di\Container;

return [
    // services
    LogServiceInterface::class => function (Container $container) {
        return new YiiLogger(\Yii::$app->log->getLogger());
    },
];
