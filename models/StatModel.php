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
 */

class StatModel extends ActiveRecord
{

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'server_id'], 'required'],
			[['id', 'server_id'], 'number', 'integerOnly' => true],
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
		];
	}

	public function getServer()
	{
		return Server::findOne(['id' => $this->server_id]);
	}
}