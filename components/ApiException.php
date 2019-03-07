<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 01.12.2018
 * Time: 22:30
 */

namespace app\components;

use app\controllers\ApiController;
use app\models\User;
use yii\helpers\VarDumper;

class ApiException extends \yii\web\HttpException
{


	public function __construct($status, $message = null, $code = 0, \Exception $previous = null)
	{
		if (in_array(\Yii::$app->request->headers['Origin'], ApiController::allowedDomains()))
			\Yii::$app->response->headers['Access-Control-Origin'] = \Yii::$app->request->headers['Origin'];
		$jsonMessage = json_encode($message);
		parent::__construct($status, $jsonMessage, $code, $previous);
	}
}