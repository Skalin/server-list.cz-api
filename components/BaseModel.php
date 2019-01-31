<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 30.01.2019
 * Time: 22:53
 */

namespace app\components;

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
}