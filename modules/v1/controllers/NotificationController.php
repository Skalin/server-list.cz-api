<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 28.04.2019
 * Time: 10:30
 */

namespace app\modules\v1\controllers;

use app\components\ApiException;
use app\controllers\ApiController;
use app\models\UserNotification;
use yii\filters\Cors;
use yii\helpers\VarDumper;
use yii\web\Response;

class NotificationController extends ApiController
{

	public $modelClass = 'app\models\UserNotification';

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
				'Access-Control-Request-Method' => ['GET', 'HEAD', 'POST', 'OPTIONS'],
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
		unset($actions['create']);
		unset($actions['delete']);
		unset($actions['update']);
		return $actions;
	}


	public function actionIndex()
	{

		VarDumper::dump(\Yii::$app->request);die;
		$user = $this->validateUser('Server');
		if (!$user)
			throw new ApiException(401, 'User not authorized.');

		$notifications = UserNotification::find()->user($user)->all();

		return $notifications;
	}


	public function actionRead($notification)
	{
		$user = $this->validateUser('Server');
		if (!$user)
			throw new ApiException(401, 'User not authorized.');


	}
}