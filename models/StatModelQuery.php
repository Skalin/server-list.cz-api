<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 25.03.2019
 * Time: 20:41
 */

namespace app\models;


use yii\db\ActiveQuery;

/**
 * Class StatModelQuery
 * @package app\models
 * @see StatModel
 */
class StatModelQuery extends ActiveQuery
{

	public function latest()
	{
		return $this->orderBy(['date' => 'desc']);
	}

	public function server($serverId)
	{
		return $this->andWhere(['server_id' => $serverId]);
	}

}