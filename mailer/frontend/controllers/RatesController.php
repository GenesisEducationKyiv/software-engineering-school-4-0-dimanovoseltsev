<?php

namespace frontend\controllers;

use app\application\actions\SubscribeInterface;
use app\application\exceptions\AlreadyException;
use app\application\exceptions\NotExistException;
use app\application\exceptions\NotSupportedException;
use app\application\exceptions\NotValidException;
use app\application\forms\SubscribeForm;
use OpenApi\Attributes as OA;
use Throwable;
use Yii;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ConflictHttpException;
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
     * @param SubscribeInterface $subscribe
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly SubscribeInterface $subscribe,
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
            $e instanceof AlreadyException => throw new ConflictHttpException(
                $e->getMessage(),
                (int)$e->getCode(),
                $e
            ),
            default => throw new BadRequestHttpException($e->getMessage(), (int)$e->getCode(), $e)
        };
    }

    /**
     * @return mixed
     * @throws BadRequestHttpException
     * @throws HttpException
     * @throws Throwable
     */
    #[OA\Post(
        path: "/subscribe",
        operationId: "subscribe",
        description: "This request should check if the given email address exists in the current database, and if not, add it.",
        summary: "Subscribe an email to receive the current exchange rate",
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: "application/x-www-form-urlencoded",
                schema: new OA\Schema(
                    required: ["email"],
                    properties: [
                        new OA\Property(
                            property: "email",
                            title: "Email",
                            description: "Email address to subscribe",
                            type: "string"
                        ),
                    ],
                    example: ["email" => "mail@mail.com"]
                ),
            )
        ),
        tags: ["Rates"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Email added"
            ),
            new OA\Response(
                response: 409,
                description: "Returned if email already exists in the database",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: "name", type: "string", example: "Conflict"),
                            new OA\Property(property: "message", type: "string", example: "Already subscribed"),
                            new OA\Property(property: "code", type: "integer", example: 0),
                            new OA\Property(property: "status", type: "integer", example: 409),
                        ],
                        type: "object",
                    )
                )
            ),
            new OA\Response(
                response: 422,
                description: "Data Validation Failed",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "array",
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "field", type: "string", example: "field1"),
                                new OA\Property(property: "message", type: "string", example: "Field1 cannot be blank"),
                            ],
                        )
                    )
                )
            ),
        ],

    )]
    public function actionSubscribe()
    {
        try {
            $bodyParams = Yii::$app->request->getBodyParams();
            $this->subscribe->execute(new SubscribeForm($bodyParams['email'] ?? null));
            return null;
        } catch (Throwable $e) {
            return $this->processException($e);
        }
    }
}