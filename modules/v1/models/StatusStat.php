<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 02.12.2018
 * Time: 15:17
 */

namespace app\modules\v1\models;

use \app\models\StatModel;
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

class StatusStat extends StatModel
{
	public $modelName = 'StatusStat';
	private static $attribute = 'status';

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'statistic_status';
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
	 * @param $date
	 * @param $server_id
	 * @param int $value
	 * @return mixed|null
	 */
	public function generateStat($date, $server_id, $value = 0)
	{
		$stat = parent::generateStat($date, $server_id);
		if ($value)
			$stat->value = $value[self::$attribute] ?? 0;
		if (!$stat->validate())
		{
			VarDumper::dump($stat->errors);
			return null;
		}
		$stat->save();
		return $stat;
	}

}