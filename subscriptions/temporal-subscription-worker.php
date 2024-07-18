<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use app\application\actions\CreateCustomerInterface;
use app\application\actions\CreateSubscriptionInterface;
use app\application\actions\DeleteCustomerInterface;
use app\application\actions\UnsubscribeInterface;
use app\infrastructure\saga\activities\SubscriptionActivities;
use app\infrastructure\saga\workflows\SubscriptionWorkflow;
use Temporal\WorkerFactory;

ini_set('display_errors', 'stderr');
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));

include "vendor/autoload.php";
require __DIR__ . '/common/env.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/common/config/bootstrap.php';
require __DIR__ . '/console/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/common/config/main.php',
    require __DIR__ . '/console/config/main.php'
);

$application = new yii\console\Application($config);

// factory initiates and runs task queue specific activity and workflow workers
$factory = WorkerFactory::create();

// Worker that listens on a task queue and hosts both workflow and activity implementations.
$factory
    ->newWorker('SubscriptionTaskQueue')
    ->registerWorkflowTypes(SubscriptionWorkflow::class)
    ->registerActivity(SubscriptionActivities::class, function () {
        return new SubscriptionActivities(
            Yii::$container->get(CreateSubscriptionInterface::class),
            Yii::$container->get(UnsubscribeInterface::class),
            Yii::$container->get(CreateCustomerInterface::class),
            Yii::$container->get(DeleteCustomerInterface::class),
        );
    });

try {
// start primary loop
    $factory->run();
} catch (Throwable $e) {
    Yii::error('Temporal Exception: ' . $e->getMessage());
}

