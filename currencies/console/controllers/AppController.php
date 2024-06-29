<?php

namespace console\controllers;

use app\currencies\application\actions\ImportRatesInterface;
use app\currencies\application\actions\RetrieveCurrencyByCodeInterface;
use app\shared\application\exceptions\NotValidException;
use app\subscriptions\application\actions\SendEmailsScheduledInterface;
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
     * @param ImportRatesInterface $importRates
     * @param SendEmailsScheduledInterface $sendEmailsScheduled
     * @param RetrieveCurrencyByCodeInterface $retrieveCurrencyByCode
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly ImportRatesInterface $importRates,
        private readonly SendEmailsScheduledInterface $sendEmailsScheduled,
        private readonly RetrieveCurrencyByCodeInterface $retrieveCurrencyByCode,
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

        $this->runAction('import-currency-rates');
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
     * @run php yii app/import-currency-rates
     */
    public function actionImportCurrencyRates(): int
    {
        try {
            $currencies = $this->importRates->execute();
            Console::output(
                sprintf('%s updated %d currencies.', date('Y-m-d H:i:s'), count($currencies))
            );
            return ExitCode::OK;
        } catch (NotValidException $e) {
            Console::error($e->getMessage());
            Console::error('Model errors: ' . var_export($e->getErrorsAsResponse(), true));
            return ExitCode::DATAERR;
        } catch (Throwable $e) {
            Console::error($e->getMessage());
            return ExitCode::DATAERR;
        }
    }

    /**
     * @run php yii app/send-emails
     */
    public function actionSendEmails(): int
    {
        try {
            $count = $this->sendEmailsScheduled->execute(
                $this->retrieveCurrencyByCode->execute((string)getenv("IMPORTED_CURRENCY")),
            );
            Console::output("Send " . $count);
            return ExitCode::OK;
        } catch (Throwable $e) {
            Console::error($e->getMessage());
            return ExitCode::DATAERR;
        }
    }
}
