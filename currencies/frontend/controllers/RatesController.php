<?php

namespace frontend\controllers;

use app\application\actions\RetrieveCurrencyByCodeInterface;
use app\application\enums\CurrencyIso;
use app\application\enums\State;
use app\application\exceptions\InvalidCallException;
use app\application\exceptions\NotExistException;
use app\application\exceptions\NotSupportedException;
use app\application\exceptions\NotValidException;
use app\application\services\MetricServiceInterface;
use OpenApi\Attributes as OA;
use Prometheus\RenderTextFormat;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

#[OA\Info(version: "1.0.0", title: "Currencies API")]
#[OA\Tag(
    name: "Currencies",
    description: "Information on API calls, parameters and responses for the Currencies API."
)]
class RatesController extends ActiveController
{
    #[OA\Schema(schema: "Rate", type: "number")]
    public $modelClass = "";

    /**
     * @param $id
     * @param $module
     * @param RetrieveCurrencyByCodeInterface $retrieveCurrencyByCode
     * @param MetricServiceInterface $metricService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly RetrieveCurrencyByCodeInterface $retrieveCurrencyByCode,
        private readonly MetricServiceInterface $metricService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
    }


    /**
     * @return array
     */
    public function actions(): array
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);

        return $actions;
    }

    /**
     * @return array|array[]
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count'
                ],
            ]
        ];

        return $behaviors;
    }


    /**
     * @throws BadRequestHttpException
     */
    protected function processException(Throwable $e): mixed
    {
        if ($e instanceof NotValidException) {
            Yii::$app->response->setStatusCode(422);
            return $e->getErrorsAsResponse();
        }

        return match (true) {
            $e instanceof NotExistException => new NotFoundHttpException(
                $e->getMessage(), (int)$e->getCode(), $e
            ),
            $e instanceof NotSupportedException => new \yii\base\NotSupportedException(
                $e->getMessage(),
                (int)$e->getCode(),
                $e
            ),
            default => new BadRequestHttpException($e->getMessage(), (int)$e->getCode(), $e)
        };
    }

    /**
     * @param float $start
     * @param int $statusCode
     * @return void
     * @throws InvalidConfigException
     */
    private function trackHttpCalls(float $start, int $statusCode): void
    {
        $endpoint = Yii::$app->request->getUrl();
        $method = Yii::$app->request->getMethod();

        $this->metricService->trackHttRequest($method, $endpoint, $statusCode);
        $this->metricService->trackHttpRequestDurations($method, $endpoint, round((float)microtime(true) - $start));
    }


    /**
     * @return array
     * @throws HttpException|Throwable
     */
    #[OA\Get(
        path: "/rate",
        description: "This request should return the current exchange rate",
        summary: "Get the current exchange rate",
        tags: ["Currencies"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Returns the current exchange rate",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: "iso3", type: "string", example: "UAH"),
                            new OA\Property(property: "rate", type: "float", example: "5.123"),
                            new OA\Property(property: "updatedAt", type: "integer", example: 1719690217),
                        ],
                        type: "object",
                    )
                )
            ),
            new OA\Response(
                response: 400,
                description: "Invalid status value",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: "name", type: "string", example: "Bad Request"),
                            new OA\Property(property: "message", type: "string", example: "Invalid status value"),
                            new OA\Property(property: "code", type: "integer", example: 0),
                            new OA\Property(property: "status", type: "integer", example: 400),
                        ],
                        type: "object",
                    )
                )
            ),
        ],
    )]
    public function actionRate(): array
    {
        $requestStart = (float)microtime(true);

        try {
            $code = (string)getenv('IMPORTED_CURRENCY');
            $currency = CurrencyIso::from($code);
            $entity = $this->retrieveCurrencyByCode->execute($currency);
            $this->trackHttpCalls($requestStart, 200);
            $this->metricService->trackCurrencyRateRequestTotal($currency, State::SUCCESSFUL);

            return [
                'iso3' => $code,
                'rate' => $entity->getRate()->value(),
                'updatedAt' => $entity->getUpdatedAt()->value(),
            ];
        } catch (NotExistException $e) {
            $this->trackHttpCalls($requestStart, 400);
            $this->metricService->trackCurrencyRateRequestTotal($currency, State::FAILURE);
            throw new HttpException(400, 'Invalid status value');
        } catch (Throwable $e) {
            $result = $this->processException($e);
            $this->metricService->trackCurrencyRateRequestTotal($currency, State::FAILURE);
            if (is_array($result)) {
                $this->trackHttpCalls($requestStart, \Yii::$app->response->getStatusCode());
                return $result;
            }
            $this->trackHttpCalls($requestStart, $e->getCode());
            throw $e;
        }
    }
}
