<?php


return [
	'' => '/',
	[
		'class' => 'yii\rest\UrlRule',
		'controller' => ['v1/user'],
		'pluralize' => false,
		'patterns' => [
			'' => 'options'
		],
		'extraPatterns' => [
			'POST server/<id>' => 'server',
			'OPTIONS server/<id>' => 'server',
		]
	],
	[
		'class' => 'yii\rest\UrlRule',
		'controller' => ['v1/user'],
		'pluralize' => false,
		'patterns' => [
			'' => 'options'
		],
		'extraPatterns' => [
			'POST login' => 'login',
			'OPTIONS login' => 'login',

		]
	],
	[
		'class' => 'yii\rest\UrlRule',
		'controller' => ['v1/service'],
	],
	[
		'class' => 'yii\rest\UrlRule',
		'controller' => ['servers' => 'v1/server'],
		'prefix' => 'v1/services/<parent:\d+>',
	],
	[
		'class' => 'yii\rest\UrlRule',
		'controller' => ['stats' => 'v1/stats'],
		'prefix' => 'v1/services/<service_id:\d+>/servers/<parent:\d+>',
	],
	[
		'class' => 'yii\rest\UrlRule',
		'controller' => ['ping-stats' => 'v1/pingStats'],
		'prefix' => 'v1/services/<service_id:\d+>/servers/<parent:\d+>',
	],
	'<controller>/<action>' => '<controller>/<action>',
];