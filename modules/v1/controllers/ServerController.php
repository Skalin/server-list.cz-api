<?php

namespace app\modules\v1\controllers;

use app\components\ApiException;
use app\controllers\ApiController;
use app\models\User;
use app\modules\v1\models\PlayersStat;
use app\modules\v1\models\Server;
use app\modules\v1\models\StatusStat;
use Codeception\Template\Api;
use DeepCopy\f001\A;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\VarDumper;
use yii\web\Response;

class ServerController extends ApiController
{

	public $objectName = "server";
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
				'Access-Control-Request-Method' => ['POST', 'PUT', 'OPTIONS', 'DELETE'],
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
		unset($actions['update']);
		return $actions;
	}

	public function actionIndex()
	{
		$subSubQuery = PlayersStat::find()
			->select(['MAX(id)'])
			->groupBy('server_id');

		$subQuery = PlayersStat::find()
			->select(['date', 'value', 'server_id'])
			->andWhere(['id' => $subSubQuery]);

		$query = Server::find()->active()
			->rightJoin(['s' => $subQuery], 's.server_id = id')
			->orderBy('s.value DESC');

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'defaultPageSize' => 12,
				'pageSize' => 9, //to set count items on one page, if not set will be set from defaultPageSize
				'pageSizeLimit' => [2, 6], //to set range for pageSize
			]
		]);
		$dataProvider->sort->sortParam = true;

		if ($this->getParentParam())
		{
			$dataProvider->query = $dataProvider->query->service($this->getParentParam());
		}


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
		$registrator = $this->getValidationMethod() == User::API_LOGIN ? $user : 1;

		if (!\Yii::$app->request->post($this->objectName, null))
		{
			throw new ApiException(422, 'Server object is missing in POST data');
		}

		$server = new Server;
		$server->attributes = \Yii::$app->request->post($this->objectName);
		$server->registrator_id = $registrator;
		$server->user_id = $user;
		$server->service_id = $this->getParentParam() ?? NULL;
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

		if (!\Yii::$app->request->post($this->objectName, null))
		{
			throw new ApiException(422, 'Server object is missing in POST data');
		}

		$server->attributes = \Yii::$app->request->post($this->objectName);
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
			return new ApiException(400);
		}

		if (!($server = Server::findById($id)))
		{
            return new ApiException(404);
		}

		if ($server->delete())
		{
			return true;
		}
        return new ApiException(400);
	}
}
