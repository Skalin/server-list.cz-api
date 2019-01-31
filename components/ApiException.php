<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 01.12.2018
 * Time: 22:30
 */

namespace app\components;

class ApiException extends \yii\web\HttpException
{


	public function __construct($status, $message = null, $code = 0, \Exception $previous = null)
	{

		$jsonMessage = json_encode($message);
		parent::__construct($status, $jsonMessage, $code, $previous);
	}
}