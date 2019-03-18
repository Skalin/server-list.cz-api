<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=serverlist.cz-api',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
	'enableSchemaCache' => false, // <- disable schema cache

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
