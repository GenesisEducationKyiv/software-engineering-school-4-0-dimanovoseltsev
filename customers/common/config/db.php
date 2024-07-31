<?php

use yii\db\Connection;

return [
    'class' => Connection::class,
    'dsn' => getenv('DB_DSN'),
    'username' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
    'tablePrefix' => getenv('DB_TABLE_PREFIX'),
    'charset' => getenv("DB_CHARSET"),
    'enableSchemaCache' => !YII_DEBUG,
    'schemaCacheDuration' => (int)getenv('DB_SCHEMA_CACHE_DURATION'),
    'schemaCache' => 'cache',
];
