<?php

namespace frontend\controllers;


use light\swagger\SwaggerAction;
use light\swagger\SwaggerApiAction;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class DocController extends Controller
{
    /**
     * @return array[]
     */
    public function actions(): array
    {
        return [
            'index' => [
                'class' => SwaggerAction::class,
                'restUrl' => Url::to('/doc/config', true),
                'title' => Yii::$app->name . ' API',
            ],
            'config' => [
                'class' => SwaggerApiAction::class,
                'scanDir' => [
                    Yii::getAlias('@frontend/controllers'),
                ],
                'enableCache' => false,
                // 'enableCache' => !YII_DEBUG,
                'api_key' => 'test'
            ],
        ];
    }
}
