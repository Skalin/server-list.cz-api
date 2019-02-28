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
use yii\helpers\VarDumper;
use yii\web\Response;

class UserController extends ApiController
{

	public $modelClass = 'app\models\User';

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
		return $actions;
	}

	public function actionCreate()
	{
		$user = new User();
		$user->attributes = \Yii::$app->request->post();

		if ($user->validate())
		{
			$user->save();
			unset($user->salt);
			unset($user->auth_key);
			unset($user->password);
			return $user;
		}
		else
		{
			throw new ApiException(400, $user->errors);
		}
	}

	public function actionLogin()
	{
		$user = User::findByUsername(\Yii::$app->request->post('username'));
		if (!$user)
		{
			throw new ApiException(404);
		}

		if (!$user->validatePassword(\Yii::$app->request->post('password')))
		{
			throw new ApiException(403, 'Incorrect username or password.');
		}

		foreach ($user->loginTokens as $loginToken)
		{
			if (!$loginToken->isExpired())
			{
				return $loginToken;
			}
		}
		$loginToken = new LoginToken();
		$date = new \DateTime(date('Y-m-d H:i:s'));
		$date->add(new \DateInterval('P30D'));
		$loginToken->expiration = $date->format('Y-m-d H:i:s');
		$loginToken->token = \Yii::$app->security->generateRandomString();
		$loginToken->link('user', $user);
		$loginToken->save();
		return $loginToken;
	}
}