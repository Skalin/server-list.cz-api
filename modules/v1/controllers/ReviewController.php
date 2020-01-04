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
use app\modules\v1\models\Review;
use app\modules\v1\models\Server;
use yii\filters\Cors;
use yii\helpers\VarDumper;
use yii\web\Response;

class ReviewController extends ApiController
{

	public $objectName = 'review';
	public $modelClass = 'app\modules\v1\models\Review';

	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$auth = $behaviors['authenticator'];
		unset($behaviors['authenticator']);

		$behaviors['corsFilter'] = [
			'class' => Cors::className(),
			'cors' => [
				'Origin' => static::allowedDomains(),
				'Access-Control-Request-Method' => ['POST', 'PUT', 'OPTIONS', 'DELETE'],
				'Access-Control-Allow-Credentials' => true,
				'Access-Control-Request-Headers' => ['x-requested-with', 'content-type'],
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


	public function actionCreate()
	{

		$user = $this->validateUser('Server');

		if (!\Yii::$app->request->post($this->objectName, null))
		{
			throw new ApiException(422, 'Server object is missing in POST data');
		}

		$review = Review::find()->user($user->id)->server(\Yii::$app->request->post('server_id'))->one();
		if ($review)
            throw new ApiException(412, 'User has already reviewed this server.');

		$review = new Review;
		$review->attributes = \Yii::$app->request->post($this->objectName);
		$review->user_id = $user;
		if ($review->save())
			return $review;

		throw new ApiException(500, 'Something went wrong during saving review.');
	}



}