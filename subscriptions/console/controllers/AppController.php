<?php

namespace console\controllers;

use app\application\actions\RetrieveActualCurrencyRateInterface;
use app\application\actions\SendEmailsScheduledInterface;
use Throwable;
use Yii;
use yii\base\InvalidRouteException;
use yii\console\Controller;
use yii\console\Exception;
use yii\console\ExitCode;
use yii\helpers\Console;


class AppController extends Controller
{
    /**
     * @param $id
     * @param $module
     * @param SendEmailsScheduledInterface $sendEmailsScheduled
     * @param RetrieveActualCurrencyRateInterface $retrieveActualCurrencyRate
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly SendEmailsScheduledInterface $sendEmailsScheduled,
        private readonly RetrieveActualCurrencyRateInterface $retrieveActualCurrencyRate,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

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
     * @run php yii app/send-emails
     */
    public function actionSendEmails(): int
    {
        try {
            $count = $this->sendEmailsScheduled->execute(
                $this->retrieveActualCurrencyRate->execute(),
            );
            Console::output("Send " . $count);
            return ExitCode::OK;
        } catch (Throwable $e) {
            Console::error($e->getMessage());
            return ExitCode::DATAERR;
        }
    }
}
