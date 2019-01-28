<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 02.12.2018
 * Time: 15:17
 */

namespace app\modules\v1\models;

use \app\models\StatModel;
use yii\db\Expression;
use yii\helpers\VarDumper;

/**
 * Class PingStat
 * @package app\components\models
 *
 * @property integer $id
 * @property integer $server_id
 * @property datetime $date
 * @property integer $value
 */

class PingStat extends StatModel
{
	public static $statName = 'Ping';

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'statistic_ping';
	}


	public function rules()
	{
		$rules = parent::rules();
		$newRules = [
			[['value'], 'number', 'integerOnly' => true]
		];
		$rules = array_merge($rules, $newRules);
		return $rules;
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		$oldAttributes = parent::attributeLabels();
		return array_merge($oldAttributes, [
			'value' => 'Value',
		]);
	}

	/**
	 * Function generates automatically the value of stat depending on service of which the server is
	 *
	 *
	 * @param $server_id
	 */
	public function generateStat($server_id)
	{
		$server = Server::findById($server_id);
		$service = $server->getService();


		$stat = new self();
		$stat->server_id = $server_id;
		$stat->value = 12;
		if (!$stat->save())
		{
			VarDumper::dump($stat->errors);die;
		}
	}

}