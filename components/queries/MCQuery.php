<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 30.01.2019
 * Time: 23:07
 */

namespace app\components\queries;

use MCServerStatus\MCPing;
use MCServerStatus\Exceptions\MCPingException;
use yii\base\Component;

class MCQuery extends Component
{
	public static function query($server)
	{

		$query = null;
		$queryResult = [];

		try {
			$query = MCPing::check($server->ip, $server->port, 5);
		} catch (MCPingException $e) {
			$Exception = $e;
		} finally {

			if (isset($query->ping)) {
				$queryResult['ping'] = $query->ping;
			}
			if (isset($query->players)) {
				$queryResult['players'] = $query->players;
			}
			if (isset($query->max_players)) {
				$queryResult['max_players'] = $query->max_players;
			}
			if (isset($query->online)) {
				$queryResult['status'] = $query->online ? 1 : 0;
			}
		}

		return $queryResult;
	}
}