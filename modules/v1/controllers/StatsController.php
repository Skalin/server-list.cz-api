<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 03.12.2018
 * Time: 20:36
 */

namespace app\modules\v1\controllers;


use app\components\ApiException;
use app\controllers\ApiController;
use app\modules\admin\modules\FileModule\models\File;
use yii\helpers\FileHelper;
use yii\web\Response;

class StatsController extends ApiController
{

	public static $tableKeys = [
		'ping',
		'players',
		//'status',
	];

	public $statModels;

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
		unset($actions['index']);
		unset($actions['update']);
		unset($actions['delete']);
		return $actions;

	}

	public function actionIndex()
	{
		$parentParam = $this->getParentParam();
		if (!$parentParam)
			return new ApiException(400);

		return $this->getAllStats($parentParam);
	}

	protected function getAllStatModels()
	{
		return self::$tableKeys;
	}

	protected function getAllStats($parentParm)
	{

		$data = [];
		foreach ($this->getAllStatModels() as $name)
		{
			$tableName = $this->getNamespace().ucfirst($name).'Stat';
			$tableName = $tableName::className();

			$data[$name] = $tableName::findAll(['server_id' => $parentParm]);
		}
		return $data;
	}

}