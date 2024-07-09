<?php

use Dotenv\Dotenv;


Dotenv::createUnsafeImmutable(dirname(__DIR__))->load();

defined('YII_DEBUG') or define('YII_DEBUG', getenv('YII_DEBUG') === 'true');
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV') ?: 'dev');
if (defined('YII_DEBUG') && YII_DEBUG) {
    error_reporting(E_ALL);
}

