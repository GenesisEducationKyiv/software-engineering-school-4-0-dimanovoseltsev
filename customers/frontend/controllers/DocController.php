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
                'title' => Yii::$app->name . " 1Api",
            ],
            'config' => [
                'class' => SwaggerApiAction::class,
                'scanDir' => [
                    Yii::getAlias('@frontend/controllers'),
                ],
                //'enableCache' => !YII_DEBUG,
                'enableCache' =>false,
                'api_key' => 'test'
            ],
        ];
    }
}
