<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 30.01.2019
 * Time: 22:53
 */

namespace app\components;

use app\modules\v1\models\Service;
use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{

	public $queryNamespace = 'app\components\queries';

	public $maximumTimeouts = 6;

	public function getClassPath()
	{
		$path = explode('\\', self::className());
		array_pop($path);
		$path = implode('\\', $path);
		return $path .= '\\';
	}

	public function getQueryPath($service)
	{
		return $this->queryNamespace.'\\'.Service::findById($service)->getQueryClass();
	}

}