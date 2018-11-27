<?php


return [
	'' => '/',
	[
		'class' => 'yii\rest\UrlRule',
		'controller' => ['v1/game','v1/server'],
	],
	[
		'class' => 'tunecino\nestedrest\UrlRule',
		'resourceName' => 'v1/games',
		'modulePrefix' => 'v1',
		'modelClass' => 'app\modules\v1\models\Game',
		'relations' => ['servers'],
	],
	[
		'class' => 'tunecino\nestedrest\UrlRule',
		'resourceName' => 'v1/servers',
		'modulePrefix' => 'v1',
		'modelClass' => 'app\modules\v1\models\Server',
		'relations' => ['game']
	],
];