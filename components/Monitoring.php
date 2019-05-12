<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 12.05.2019
 * Time: 13:54
 */

namespace app\components;


class Monitoring extends \Threaded
{

	private $servers;

	public function __construct($servers)
	{
		$this->servers = $servers;
	}

	public function run()
	{
		$date = $this->getStartDate();

		$failedServers[0] = [];

		foreach ($this->servers as $server)
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
	private function getStartDate()
	{
		$startDate = (new \DateTime());
		$startDate->add(new \DateInterval("PT2H"));
		return $startDate->format('Y-m-d H:i:s');
	}
}