<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 27.11.2018
 * Time: 22:00
 */

namespace app\controllers;


use app\components\ApiException;
use app\models\User;
use app\modules\v1\models\Server;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\rest\ActiveController;
use yii\web\HttpException;

class ApiController extends ActiveController
{

	public $apiVersion = 'v1';

	public $namespace;

	public function getNamespace()
	{
		return "app\modules\\{$this->apiVersion}\models\\";
	}

	/**
	 *
	 *
	 * @internal this function doesn't properly get all params, it gets only the last param from all params,
	 * i must write a special url rest route controller to properly parse all parent params and pass them with
	 * controllers (models) as return value
	 *
	 */
	public function getParentParam()
	{
		return isset(\Yii::$app->request->getQueryParams()['parent']) ? \Yii::$app->request->getQueryParams()['parent'] : null;
	}

	protected function getValidationMethod()
	{
		if (\Yii::$app->request->post('login_token', null))
		{
			return User::WEB_LOGIN;
		}
		if (\Yii::$app->request->post('registrator_token', null))
		{
			return User::API_LOGIN;
		}

		return null;
	}

	protected function getValidationData()
	{

		$validationMethod = $this->getValidationMethod();

		if ($validationMethod === User::WEB_LOGIN)
		{
			return \Yii::$app->request->post('login_token');
		}
		if ($validationMethod === User::API_LOGIN)
		{
			return \Yii::$app->request->post('registrator_token');
		}

		return null;
	}

	public function validateUser($modelName)
	{

		if (!$validationMethod = $this->getValidationMethod())
		{
			throw new ApiException(403);
		}

		$user = User::findByAccessToken($this->getValidationMethod(), $this->getValidationData());
		if (!$user)
			throw new ApiException(403);

		return $user->id;
	}
}