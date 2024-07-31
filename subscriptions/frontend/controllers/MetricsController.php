<?php

namespace frontend\controllers;

use app\application\services\MetricServiceInterface;
use OpenApi\Attributes as OA;
use Prometheus\RenderTextFormat;
use Yii;
use yii\web\Controller;

#[OA\Tag(
    name: "Metrics",
    description: "Information on API calls, parameters and responses for the Metrics API"
)]
class MetricsController extends Controller
{
    public $modelClass = "";

    /**
     * @param $id
     * @param $module
     * @param MetricServiceInterface $metricService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
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
     * @return string
     */
    #[OA\Get(
        path: "/metrics",
        description: "This endpoint retrieves the metrics data",
        summary: "Retrieve Metrics Data",
        tags: ["Metrics"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful response",


                content: new OA\MediaType(
                    mediaType: "text/plain",
                    schema: new OA\Schema(type: "string")
                )
            ),
        ],
    )]
    public function actionIndex()
    {
        Yii::$app->response->headers->set('Content-Type', RenderTextFormat::MIME_TYPE);
        return $this->metricService->export();
    }
}
