<?php

namespace app\modules\v1\controllers;

use app\controllers\ApiController;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\web\Response;

class ServiceController extends ApiController
{

	public $modelClass = 'app\modules\v1\models\Stats';

	public function behaviors()
	{
		$behaviors = parent::behaviors();
		unset($behaviors['authenticator']);

		$behaviors['contentNegotiator'] = [
				'class' => 'yii\filters\ContentNegotiator',
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
				]
		];/*
		$behaviors['authenticator'] = [
				'class' => HttpBasicAuth::className(),
		];
*/
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

}
