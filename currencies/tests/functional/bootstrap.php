<?php

use Codeception\Util\Autoload;

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('ENABLE_LOG') or define('ENABLE_LOG', false);
defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', dirname(__DIR__, 2));


require_once(YII_APP_BASE_PATH . '/vendor/autoload.php');
require_once(YII_APP_BASE_PATH . '/common/env.php');
require(YII_APP_BASE_PATH . '/vendor/yiisoft/yii2/Yii.php');
require(YII_APP_BASE_PATH . '/common/config/bootstrap.php');
require(YII_APP_BASE_PATH . '/frontend/config/bootstrap.php');
require(YII_APP_BASE_PATH . '/console/config/bootstrap.php');
require(YII_APP_BASE_PATH . '/tests/config/bootstrap.php');

Autoload::addNamespace('tests', dirname(__FILE__, 2));

$config = yii\helpers\ArrayHelper::merge(
    require(YII_APP_BASE_PATH . '/common/config/main.php'),
    require(YII_APP_BASE_PATH . '/frontend/config/main.php'),
    require(YII_APP_BASE_PATH . '/console/config/main.php'),
    require(YII_APP_BASE_PATH . '/tests/config/functional.php'),
);

(new yii\web\Application($config));
Yii::setAlias('@tests', dirname(__DIR__));
