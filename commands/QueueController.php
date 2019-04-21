<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 28.01.2019
 * Time: 20:05
 */

namespace app\commands;

use app\modules\v1\models\Server;
use app\modules\v1\models\PingStat;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Expression;

class QueueController extends Controller
{

	public function actionGenerate()
	{
		$startDate = (new \DateTime());
		$startDate->add(new \DateInterval("PT2H"));
		$startDate = $startDate->format('Y-m-d H:i:s');
		$servers = Server::find()->all();
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
			$server->destroyOldStatistics();

			if (!$server->generateStatistics($startDate))
				$failedServerArray[] = $server;
		}

		echo "Waiting for 120 seconds until last testing of failed servers.\n";
		sleep(120);

		foreach ($failedServerArray as $server)
		{

			$server->destroyOldStatistics($startDate);
			$server->generateStatistics(true);
		}

		return ExitCode::OK;
	}

}