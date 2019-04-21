<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 02.12.2018
 * Time: 15:17
 */

namespace app\modules\v1\models;

use \app\models\StatModel;

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
	public $modelName = 'PingStat';
	private static $attribute = 'ping';

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
			[['value'], 'number', 'integerOnly' => true],
			[['value'], 'required']
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
	 * @param $server_id
	 * @return StatModel|null
	 */
	public function generateStat($date, $server_id, $value = NULL)
	{
		$stat = parent::generateStat($server_id);
		if ($value)
			$stat->value = $value[self::$attribute] ?? null;
		if (!$stat->validate())
			return null;
		return $stat;
	}

}