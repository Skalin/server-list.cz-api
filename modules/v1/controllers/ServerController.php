<?php

namespace app\modules\v1\controllers;

use app\components\ApiException;
use app\controllers\ApiController;
use app\modules\v1\models\Server;
use Codeception\Template\Api;
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
		unset($actions['view']);
		unset($actions['create']);
		return $actions;
	}

	public function actionIndex()
	{
		if (!$this->getParentParam())
		{
			return Server::find()->all();
		}

		return Server::findAll(['game_id' => $this->getParentParam()]);
	}

	public function actionView()
	{
		$id = \Yii::$app->request->getQueryParams()['id'] ?? null;

		if (!$id)
		{
			return new ApiException(400);
		}

		if (!($server = Server::findOne(['id' => $id])))
		{
			return new ApiException(404);
		}

		if (!$this->getParentParam())
		{
			return $server;
		}

		return $server->game_id != $this->getParentParam() ? new ApiException(404) : $server;
	}

	/**
	 * POST function for creating server, the request is called only ending with controller name, do not add action name => url = v1/servers
	 *
	 */
	public function actionCreate()
	{
		$server = new Server;
		$server->attributes = \Yii::$app->request->post();
		if ($server->validate())
		{
			$server->save();
			return $server;
		}
		else
		{
			return new ApiException(400, $server->errors);
		}
	}
}
