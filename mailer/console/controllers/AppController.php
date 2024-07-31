<?php

namespace console\controllers;

use app\application\actions\SendEmailsScheduledInterface;
use app\application\enums\CurrencyIso;
use app\application\enums\State;
use app\application\services\MetricServiceInterface;
use Throwable;
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
     * @param $id
     * @param $module
     * @param SendEmailsScheduledInterface $sendEmailsScheduled
     * @param MetricServiceInterface $metricService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly SendEmailsScheduledInterface $sendEmailsScheduled,
        private readonly MetricServiceInterface $metricService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

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
        $command = 'app/send-emails';
        $start = (float)microtime(true);

        try {
            $count = $this->sendEmailsScheduled->execute(CurrencyIso::UAH);
            Console::output("Send " . $count);

            $this->metricService->trackCommands($command, State::SUCCESSFUL);
            return ExitCode::OK;
        } catch (Throwable $e) {
            Console::error($e->getMessage());

            $this->metricService->trackCommands($command, State::FAILURE);
            $this->metricService->trackCommandsDurations($command, round((float)microtime(true) - $start));

            return ExitCode::DATAERR;
        }
    }
}
