<?php

namespace app\modules\v1\controllers;

use app\controllers\ApiController;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\VarDumper;
use yii\web\Response;

class ServiceController extends ApiController
{

	public $modelClass = 'app\modules\v1\models\Service';

	public function behaviors()
	{
		$behaviors = parent::behaviors();
		unset($behaviors['authenticator']);

		// add CORS filter
		$behaviors['corsFilter'] = [
			'class' => Cors::className(),
			'cors' => [
				'Origin' => static::allowedDomains(),
				'Access-Control-Request-Method' => ['GET', 'HEAD'],
				'Access-Control-Request-Headers' => ['*'],
				'Access-Control-Allow-Credentials' => true,
			],
		];

		$behaviors['contentNegotiator'] = [
				'class' => 'yii\filters\ContentNegotiator',
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
				]
		];
		return $behaviors;
	}

	public function actions()
	{
		$actions = parent::actions();
		unset($actions['create']);
		unset($actions['update']);
		unset($actions['delete']);
		return $actions;
	}

	//todo create a view function that will parse a shortcut and treat it as ID or create special action that will grant all IDs and shortcuts

}
