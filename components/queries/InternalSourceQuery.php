<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 30.01.2019
 * Time: 23:34
 */

namespace app\components\queries;

use xPaw\SourceQuery\SourceQuery;

class InternalSourceQuery extends BaseQuery
{

	public static function query($server)
	{

		$query = new SourceQuery();
		$info = null;
		$ping = null;
		$time = microtime(true);

		$queryResult = [];

		try
		{
			$query->Connect($server->ip, $server->port, 5);

            $info = $query->GetInfo();
		}
		catch ( Exception $e)
		{

		}
		finally
		{
			$finishTime = microtime(true);
			$finishTime -= $time;
			$queryResult['ping'] = round($finishTime*100);
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