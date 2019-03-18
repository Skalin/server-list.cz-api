<?php

return [
    'class' => 'yii\db\Connection',
	'dsn' => 'mysql:host=127.0.0.1;dbname=db',
	'username' => 'root',
	'password' => 'UsT5Rp4MhreSmd86',
    'charset' => 'utf8',
	'enableSchemaCache' => false, // <- disable schema cache

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
