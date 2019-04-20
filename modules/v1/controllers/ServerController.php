<?php

namespace app\modules\v1\controllers;

use app\components\ApiException;
use app\controllers\ApiController;
use app\modules\v1\models\Server;
use app\modules\v1\models\StatusStat;
use Codeception\Template\Api;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\VarDumper;
use yii\web\Response;

class ServerController extends ApiController
{

	public $modelClass = 'app\modules\v1\models\Server';


	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$auth = $behaviors['authenticator'];
		unset($behaviors['authenticator']);

		// add CORS filter
		$behaviors['corsFilter'] = [
			'class' => Cors::className(),
			'cors'  => [
				// restrict access to domains:
				'Origin' => static::allowedDomains(),
				'Access-Control-Request-Method' => ['POST', 'PUT', 'OPTIONS'],
				'Access-Control-Allow-Credentials' => true,
				'Access-Control-Request-Headers' => ['x-requested-with', 'content-type'],
				'Access-Control-Max-Age' => 3600, // Cache (seconds)
			],
		];

		$behaviors['contentNegotiator'] = [
			'class' => 'yii\filters\ContentNegotiator',
			'formats' => [
				'application/json' => Response::FORMAT_JSON,
			]
		];
		// re-add authentication filter
		$behaviors['authenticator'] = $auth;
		// avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
		$behaviors['authenticator']['except'] = ['options'];

		return $behaviors;
	}

	public function actions()
	{
		$actions = parent::actions();
		unset($actions['index']);
		unset($actions['view']);
		unset($actions['create']);
		unset($actions['delete']);
		return $actions;
	}

	public function actionIndex()
	{

		$dataProvider = new ActiveDataProvider([
			'query' => StatusStat::find()
				->joinWith('server as s')
				->orderBy('date, value DESC')
				->groupBy('server_id'),
		]);
		/*
		$dataProvider = new ActiveDataProvider([
			'query' => Server::find()
				->joinWith('statusStatistics as ss')
				->orderBy('ss.date, ss.value DESC')
				->joinWith('playersStatistics as ps')
				->addOrderBy('ps.value DESC'),
			'pagination' => [
				'defaultPageSize' => 12,
				'pageSize' => 12, //to set count items on one page, if not set will be set from defaultPageSize
				'pageSizeLimit' => [2, 24], //to set range for pageSize
			]
		]);*/
		$dataProvider->sort->sortParam = true;


		if (!$this->getParentParam())
		{
			return $dataProvider;
		}

		$dataProvider->query = $dataProvider->query->service(['service_id' => $this->getParentParam()]);

		return $dataProvider;
	}

	public function actionView()
	{
		$id = \Yii::$app->request->getQueryParams()['id'] ?? null;

		if (!$id)
		{
			return new ApiException(400);
		}

		if (!($server = Server::findById($id)))
		{
			return new ApiException(404);
		}

		if (!$this->getParentParam())
		{
			return $server;
		}

		return $server->service_id != $this->getParentParam() ? new ApiException(404) : $server;
	}

	/**
	 * POST function for creating server, the request is called only ending with controller name, do not add action name => url = v1/servers
	 *
	 */
	public function actionCreate()
	{
		$user = $this->validateUser('Server');

		$server = new Server;
		$server->attributes = \Yii::$app->request->post("server");
		$server->registrator_id = 1;
		$server->user_id = $user;
		if ($server->validate())
		{
			$server->save();
			return $server;
		}
		else
		{
			throw new ApiException(400, $server->errors);
		}
	}

	public function actionUpdate()
	{

		$this->validateUser('Server');

		$id = \Yii::$app->request->getQueryParams()['id'] ?? null;

		if (!$id)
		{
			return new ApiException(400);
		}

		if (!($server = Server::findById($id)))
		{
			return new ApiException(404);
		}

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

	public function actionDelete()
	{

		$this->validateUser('Server');

		$id = \Yii::$app->request->getQueryParams()['id'] ?? null;

		if (!$id)
		{
			throw new ApiException(400);
		}

		if (!($server = Server::findById($id)))
		{
			throw new ApiException(404);
		}

		if ($server->delete())
		{
			return true;
		}
		throw new ApiException(400);
	}
}
