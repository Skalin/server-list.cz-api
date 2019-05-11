<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 28.01.2019
 * Time: 20:05
 */

namespace app\commands;

use app\components\BackgroundWorker;
use app\modules\v1\models\Server;
use app\modules\v1\models\Service;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Expression;
use yii\helpers\VarDumper;

class QueueController extends Controller
{

	const MAX_SERVER_COUNT = 200;

	public function actionGenerate()
	{
		$serverChunks = Server::find()
			->select(['monitoring_chunk'])
			->groupBy(['monitoring_chunk'])
			->asArray()
			->all();
		$startDate = $this->getStartDate();

		foreach ($serverChunks as $serverChunk)
		{
			exec("nohup /var/www/server-list.cz-api/yii queue/generate-stats \"{$startDate}\" {$serverChunk['monitoring_chunk']} >/dev/null 2>&1");
		}

		return ExitCode::OK;
	}

	public function actionGenerateStats($date, $serverChunk)
	{

		if (!$date)
			$date = $this->getStartDate();

		$chunk = intval($serverChunk);
		$servers = Server::findByChunk($chunk);

		$failedServers[0] = [];

		foreach ($servers as $server)
		{
			$server->destroyOldStatistics();

			if (!$server->generateStatistics($date))
				$failedServers[0][] = $server;
		}

		echo "Waiting for 120 seconds until another testing of failed servers.\n";
		sleep(120);

		$failedServers[1] = [];
		foreach ($failedServers[0] as $server)
		{
			if (!$server->generateStatistics($date))
				$failedServers[1][] = $server;
		}

		echo "Waiting for 120 seconds until last testing of failed servers.\n";
		sleep(120);

		foreach ($failedServers[1] as $server)
		{
			$server->generateStatistics($date, true);
		}
		echo "Waiting for 120 seconds until last testing of failed servers.\n";
	}

	/**
	 * @param $servers array
	 * @return array
	 */
	private function splitServersIntoChunks($servers)
	{
		return array_chunk($servers, self::MAX_SERVER_COUNT);
	}

	private function getStartDate()
	{
		$startDate = (new \DateTime());
		$startDate->add(new \DateInterval("PT2H"));
		return $startDate->format('Y-m-d H:i:s');
	}

}