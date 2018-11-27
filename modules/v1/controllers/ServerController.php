<?php

namespace app\modules\v1\controllers;

use app\controllers\ApiController;
use app\modules\v1\models\Server;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\web\Response;

class ServerController extends ApiController
{

	public $modelClass = 'app\modules\v1\models\Server';


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
		unset($actions['index']);
		return $actions;
	}

	public function actionIndex()
	{
		if (!$this->getParentParam())
		{
			return Server::find()->all();
		}

		return Server::find()->where(['game_id' => $this->getParentParam()])->all();
	}
}
