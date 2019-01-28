<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 02.12.2018
 * Time: 15:14
 */

namespace app\models;


use app\modules\v1\models\Server;
use yii\db\ActiveRecord;

/**
 * Class BasicStatModel
 * @package app\components\models
 *
 * @property integer $id
 * @property integer $server_id
 * @property datetime $date
 */

class StatModel extends ActiveRecord
{

	const STAT_NAME = '';

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['server_id'], 'required'],
			[['date'], 'datetime'],
			[['server_id'], 'exist', 'skipOnError' => true, 'targetClass' => Server::className(), 'targetAttribute' => ['server_id' => 'id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'server_id' => 'Server ID',
			'date' => 'Date',
		];
	}



	public static function destroyOldStatistics($server_id)
	{
		$date = new \DateTime();
		$date->format('Y-m-d H:i:s');
		$date->sub(new \DateInterval('P14D'));
		self::deleteAll(
			'server_id = :sid AND date < :date',
			[':sid' => $server_id, ':date' => $date->format('Y-m-d H:i:s')]);
	}

	public function generateStat($server_id){}

	public function getServer()
	{
		return Server::findOne(['id' => $this->server_id]);
	}
}