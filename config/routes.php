<?php


return [
	'' => '/',
	[
		'class' => 'yii\rest\UrlRule',
		'controller' => ['v1/game','v1/server'],
	],
	[
		'class' => 'yii\rest\UrlRule',
		'controller' => ['servers' => 'v1/server'],
		'prefix' => 'v1/games/<parent>',
	],
	'<controller>/<action>' => '<controller>/<action>',
];