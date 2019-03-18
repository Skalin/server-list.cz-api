<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 18.03.2019
 * Time: 10:12
 */

namespace app\components\queries;


use yii\base\Component;

class BaseQuery extends Component
{
	public static function query($server)
	{
		return $server;
	}

	public static function getStatus($server)
	{
		return get_called_class()::query($server)['status'];
	}
}