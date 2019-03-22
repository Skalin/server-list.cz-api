<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 27.02.2019
 * Time: 23:33
 */

namespace app\modules\v1\controllers;


use app\components\ApiException;
use app\controllers\ApiController;
use app\models\LoginToken;
use app\models\User;
use app\modules\v1\models\Server;
use yii\filters\Cors;
use yii\helpers\VarDumper;
use yii\web\Response;

class UserController extends ApiController
{

	public $modelClass = 'app\models\User';


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
				'Access-Control-Request-Method' => ['POST', 'OPTIONS'],
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
		unset($actions['create']);
		unset($actions['index']);
		return $actions;
	}

	public function actionRegister()
	{
		$user = new User();
		$user->attributes = \Yii::$app->request->post('user');

		if ($user->validate())
		{
			$user->save();
			return $user->generateLoginToken();
		}
		else
		{
			throw new ApiException(400, $user->errors);
		}
	}

	public function actionLogin()
	{
		$data = \Yii::$app->request->post('user');
		$user = User::findByUsername($data['username']);
		if (!$user)
		{
			throw new ApiException(401, 'Username not found');
		}

		if (!$user->validatePassword($data['password']))
		{
			throw new ApiException(401, 'Incorrect username or password.');
		}

		foreach ($user->loginTokens as $loginToken)
		{
			if (!$loginToken->isExpired())
			{
				return $loginToken;
			}
		}
		$loginToken = new LoginToken(['user' => $user]);
		$loginToken->save();
		return $loginToken;
	}

	public function actionServer($id)
	{

		$user = $this->validateUser('Server');
		if (!$user)
			throw new ApiException(401, 'User not authorized.');

		$criteria = ['id' => $id];
		$server = Server::findOne($criteria);
		if (!$server)
			throw new ApiException(404, 'Server not found!');

		$server = Server::findOne(array_merge($criteria, ['user_id' => $user]));
		if (!$server)
			throw new ApiException(403, 'Not users server.');

		return true;
	}

}