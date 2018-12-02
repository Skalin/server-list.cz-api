<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 02.12.2018
 * Time: 15:17
 */

namespace app\modules\v1\models;

use \app\models\StatModel;

class PingStat extends StatModel
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'statistic_ping';
	}

}