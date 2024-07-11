<?php

return [
    'id' => 'app-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'user' => [
            'class' => \yii\web\User::class,
            'identityClass' => \common\models\User::class,
        ],
    ],
];
