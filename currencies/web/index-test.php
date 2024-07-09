<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', dirname(__DIR__, 1));



require YII_APP_BASE_PATH . '/vendor/autoload.php';
require YII_APP_BASE_PATH . '/common/env.php';

require YII_APP_BASE_PATH . '/vendor/yiisoft/yii2/Yii.php';
require YII_APP_BASE_PATH . '/common/config/bootstrap.php';
require YII_APP_BASE_PATH . '/frontend/config/bootstrap.php';
require YII_APP_BASE_PATH . '/tests/config/bootstrap.php';
$config = require YII_APP_BASE_PATH . '/tests/config/functional.php';


(new yii\web\Application($config))->run();
