<?php

namespace frontend\controllers;

use app\application\actions\RetrieveCurrencyByCodeInterface;
use app\application\exceptions\NotExistException;
use app\application\exceptions\NotSupportedException;
use app\application\exceptions\NotValidException;
use OpenApi\Attributes as OA;
use Throwable;
use Yii;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

#[OA\Info(version: "1.0.0", title: "Currency Rates API")]
#[OA\Tag(
    name: "Rates",
    description: "Information on API calls, parameters and responses for the Rates API."
)]
class RatesController extends ActiveController
{
    #[OA\Schema(schema: "Rate", type: "number")]
    public $modelClass = "";

    /**
     * @param $id
     * @param $module
     * @param RetrieveCurrencyByCodeInterface $retrieveCurrencyByCode
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly RetrieveCurrencyByCodeInterface $retrieveCurrencyByCode,
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
     * @throws \yii\base\NotSupportedException
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    protected function processException(Throwable $e): mixed
    {
        if ($e instanceof NotValidException) {
            Yii::$app->response->setStatusCode(422);
            return $e->getErrorsAsResponse();
        }

        return match (true) {
            $e instanceof NotExistException => throw new NotFoundHttpException(
                $e->getMessage(), (int)$e->getCode(), $e
            ),
            $e instanceof NotSupportedException => throw new \yii\base\NotSupportedException(
                $e->getMessage(),
                (int)$e->getCode(),
                $e
            ),
            default => throw new BadRequestHttpException($e->getMessage(), (int)$e->getCode(), $e)
        };
    }


    /**
     * @return float
     * @throws HttpException|Throwable
     */
    #[OA\Get(
        path: "/rate",
        description: "This request should return the current exchange rate",
        summary: "Get the current exchange rate",
        tags: ["Rates"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Returns the current exchange rate",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(type: "number")
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
    public function actionRate(): float
    {
        try {
            $code = (string)getenv('IMPORTED_CURRENCY');
            $entity = $this->retrieveCurrencyByCode->execute($code);
            return $entity->getRate()->value();
        } catch (NotExistException $e) {
            throw new HttpException(400, 'Invalid status value');
        } catch (Throwable $e) {
            return $this->processException($e);
        }
    }
}
