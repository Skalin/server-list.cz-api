<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 30.01.2019
 * Time: 23:34
 */

namespace app\components\queries;

use xPaw\SourceQuery\SourceQuery;

use yii\base\Component;

class InternalSourceQuery extends Component
{

	public static function query($server)
	{

		$query = new SourceQuery();
		$info = null;
		$ping = null;

		$queryResult = [];

		try
		{
			$query->Connect($server->ip, $server->port, 5);

			$ping = $query->Ping();
			if ($ping)
			{
				$info = $query->GetInfo();
			}
		}
		catch ( Exception $e)
		{

		}
		finally
		{
			if (isset($info['Players']))
			{
				$queryResult['players'] = $info['Players'];
			}
			if (isset($info['MaxPlayers']))
			{
				$queryResult['max_players'] = $info['MaxPlayers'];
			}
			$queryResult['status'] = $ping ? 1 : 0;

			$query->Disconnect();
		}

		return $queryResult;
	}

}