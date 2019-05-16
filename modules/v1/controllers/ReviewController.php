<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 16.05.2019
 * Time: 21:13
 */

namespace app\modules\v1\controllers;


use app\components\ApiException;
use app\controllers\ApiController;
use app\modules\v1\models\Server;
use yii\filters\Cors;
use yii\helpers\VarDumper;
use yii\web\Response;

class ReviewController extends ApiController
{

	public $modelClass = 'app\modules\v1\models\Review';

	public function behaviors()
	{
		$behaviors = parent::behaviors();
		unset($behaviors['authenticator']);
		$behaviors['corsFilter'] = [
			'class' => Cors::className(),
			'cors' => [
				'Origin' => static::allowedDomains(),
				'Access-Control-Request-Method' => ['GET', 'HEAD'],
				'Access-Control-Request-Headers' => ['*'],
				'Access-Control-Allow-Credentials' => true,
			],
		];

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
		unset($actions['update']);
		unset($actions['delete']);
		return $actions;
	}

	public function actionIndex()
	{

		$parentParam = $this->getParentParam();
		if (!$parentParam)
			throw new ApiException(400, 'Incorrect API call!');


		if (!$server = Server::findById($parentParam))
			throw new ApiException(404, 'Server not found!');

		return $server->calculateReviews();
	}



}