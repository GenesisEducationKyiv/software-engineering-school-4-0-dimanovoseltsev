<?php

namespace console\controllers;

use app\infrastructure\saga\workflows\SubscriptionWorkflowInterface;
use Carbon\CarbonInterval;
use Temporal\Client\GRPC\ServiceClient;
use Temporal\Client\WorkflowClient;
use Temporal\Client\WorkflowOptions;
use Yii;
use yii\base\InvalidRouteException;
use yii\console\Controller;
use yii\console\Exception;
use yii\console\ExitCode;
use yii\helpers\Console;

class AppController extends Controller
{
    public array $writablePaths = [
        '@common/runtime',
        '@console/runtime',
        '@tests/runtime',
        '@root/web/assets',
    ];

    public array $executablePaths = [
        '@root/yii',
    ];

    /**
     * @throws InvalidRouteException
     * @throws Exception
     * @run php yii app/init
     */
    public function actionInit(): int
    {
        $this->runAction('set-writable', ['interactive' => $this->interactive]);
        $this->runAction('set-executable', ['interactive' => $this->interactive]);
        Yii::$app->runAction('migrate/up', ['interactive' => $this->interactive]);

        return ExitCode::OK;
    }

    /**
     * @return void
     * @run php yii app/set-writable
     */
    public function actionSetWritable(): void
    {
        foreach ($this->writablePaths as $path) {
            $path = Yii::getAlias($path);
            Console::output("Setting writable: {$path}");
            @chmod($path, 0777);
        }
    }

    /**
     * @return void
     * @run php yii app/set-executable
     */
    public function actionSetExecutable(): void
    {
        foreach ($this->executablePaths as $path) {
            $path = Yii::getAlias($path);
            Console::output("Setting executable: {$path}");
            @chmod($path, 0755);
        }
    }

    /**
     * @return int
     * @run php yii app/temporal-test
     */
    public function actionTemporalTest(): int
    {
        $email = 'mail@mail.com';

        // Create a GRPC service client with the Temporal server address
        $serviceClient = ServiceClient::create('temporal:7233');

        // Start a Workflow
        $client = WorkflowClient::create($serviceClient);


        $workflow = $client->newWorkflowStub(
            SubscriptionWorkflowInterface::class,
            WorkflowOptions::new()->withWorkflowExecutionTimeout(CarbonInterval::minute())
                ->withTaskQueue('SubscriptionTaskQueue')
        );


        // @todo debug
        echo '<pre>';
        print_r(array(
            'file' => __FILE__,
            'line' => __LINE__,
            '$workflow' => get_class($workflow),
            '$client' => get_class($client),
        ));
        echo '</pre>';
        die();



        try {
            $run = $client->start($workflow, $email);
        } catch (\Throwable $e) {
            // @todo debug
            echo '<pre>';
            print_r(array(
                'file' => __FILE__,
                'line' => __LINE__,
                'getMessage' => $e->getMessage(),
                'get_class' => get_class($e),
            ));
            echo '</pre>';
            die();
        }


        // @todo debug
        echo '<pre>';
        print_r(array(
            'file' => __FILE__,
            'line' => __LINE__,
            'line' => $run->getResult(),
        ));
        echo '</pre>';
        die();


        // @todo debug
        echo '<pre>';
        print_r(array(
            'file' => __FILE__,
            '$workflow' => $workflow,
        ));
        echo '</pre>';
        die();


        die();
    }
}
