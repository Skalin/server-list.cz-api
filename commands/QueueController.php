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

class QueueController extends Controller
{

	public function actionGenerate()
	{
		$servers = Server::find()->all();
		if (!$servers)
			return ExitCode::NOINPUT;

		foreach ($servers as $server)
		{
			$server->destroyOldStatistics();
			$server->generateStatistics();
		}

		return ExitCode::OK;
	}

}