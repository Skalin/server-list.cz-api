<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 28.01.2019
 * Time: 20:05
 */

namespace app\commands;

use app\components\Monitoring;
use app\components\Pool;
use app\components\Task;
use app\modules\v1\models\Server;
use devmastersbv\pthreads\Data;
use devmastersbv\pthreads\SafeLog;
use yii\console\Controller;
use yii\console\ExitCode;

class QueueController extends Controller
{

    public $chunk;

	const MAX_SERVER_COUNT = 1;


	public function actionGenerate()
	{
		$startDate = $this->getStartDate();

		if ($this->chunk)
            $servers = Server::find()->loggable()->chunk($this->chunk)->all();
		else
            $servers = Server::find()->loggable()->all();
		if (!$servers)
			return ExitCode::NOINPUT;

		$failedServers = [];

		foreach ($servers as $server)
		{
			$server->destroyOldStatistics();

			if (!$server->generateStatistics($startDate))
				$failedServers[] = $server;
		}

		echo "Waiting for 120 seconds until another testing of failed servers.\n";
		sleep(120);


		$failedServerArray = [];
		foreach ($failedServers as $server)
		{
			if (!$server->generateStatistics($startDate))
				$failedServerArray[] = $server;
		}

		echo "Waiting for 120 seconds until last testing of failed servers.\n";
		sleep(120);

		foreach ($failedServerArray as $server)
		{
			$server->generateStatistics($startDate, true);
		}

		return ExitCode::OK;
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