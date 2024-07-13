<?php

namespace frontend\controllers;

use app\application\actions\DeleteCustomerInterface;
use app\application\actions\SaveCustomerInterface;
use app\application\exceptions\AlreadyException;
use app\application\exceptions\NotExistException;
use app\application\exceptions\NotSupportedException;
use app\application\exceptions\NotValidException;
use app\application\forms\DeleteCustomerForm;
use app\application\forms\SaveCustomerForm;
use OpenApi\Attributes as OA;
use Throwable;
use Yii;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ConflictHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

#[OA\Info(version: "1.0.0", title: "Customers API")]
#[OA\Tag(
    name: "Customers",
    description: "Information on API calls, parameters and responses for the Customers API."
)]
class CustomersController extends ActiveController
{
    public $modelClass = "";

    /**
     * @param $id
     * @param $module
     * @param SaveCustomerInterface $saveCustomer
     * @param DeleteCustomerInterface $deleteCustomer
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly SaveCustomerInterface $saveCustomer,
        private readonly DeleteCustomerInterface $deleteCustomer,
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
        path: "/customer",
        operationId: "create",
        summary: "Create customer",
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
        tags: ["Customers"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Email added"
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
    public function actionCreate()
    {
        try {
            $bodyParams = Yii::$app->request->getBodyParams();
            $this->saveCustomer->execute(new SaveCustomerForm($bodyParams['email'] ?? null));
            return null;
        } catch (Throwable $e) {
            return $this->processException($e);
        }
    }


    /**
     * @return void
     */
    #[OA\Delete(
        path: "/customer",
        operationId: "Remove",
        summary: "Remove customer",
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
        tags: ["Customers"],
        responses: [
            new OA\Response(response: 204, description: "No Content"),
            new OA\Response(
                response: 404,
                description: "Not found",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: "name", type: "string", example: "Not found"),
                            new OA\Property(property: "message", type: "string", example: "Not exist"),
                            new OA\Property(property: "code", type: "integer", example: 0),
                            new OA\Property(property: "status", type: "integer", example: 404),
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
    public function actionDelete()
    {
        try {
            $bodyParams = Yii::$app->request->getBodyParams();
            $this->deleteCustomer->execute(new DeleteCustomerForm($bodyParams['email'] ?? null));
            return null;
        } catch (Throwable $e) {
            return $this->processException($e);
        }
    }
}
