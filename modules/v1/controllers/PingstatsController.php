<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 03.12.2018
 * Time: 20:36
 */

namespace app\modules\v1\controllers;


use yii\web\Response;

class PingstatsController extends StatsController
{

	public $modelClass = 'app\modules\v1\models\PingStat';

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
		unset($actions['update']);
		unset($actions['delete']);
		return $actions;
	}

	public function actionView()
	{
		return $this->getParentParam();
	}


}