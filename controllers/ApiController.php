<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 27.11.2018
 * Time: 22:00
 */

namespace app\controllers;


use yii\helpers\Inflector;
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
}