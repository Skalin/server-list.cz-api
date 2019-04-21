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

class PlayersStat extends StatModel
{
	public $modelName = 'PlayersStat';
	private static $attribute = 'players';
	private static $secondAttribute = 'max_players';
	/**
	 * {@inheritdoc}
	 */
	public static final function tableName()
	{
		return 'statistic_players';
	}

	public function rules()
	{
		$rules = parent::rules();
		$newRules = [
			[['value', 'maxValue'], 'number', 'integerOnly' => true],
			[['value', 'maxValue'], 'required'],
		];
		$rules = array_merge($rules, $newRules);
		return $rules;
	}

	public function generateStat($date, $server_id, $value = 0)
	{
		$stat = parent::generateStat($date, $server_id, $value);
		if ($value)
		{
			$stat->value = $value[self::$attribute] ?? 0;
			$stat->maxValue = $value[self::$secondAttribute] ?? 0;
		}
		if (!$stat->validate())
		{
			VarDumper::dump($stat->errors);
			return null;
		}
		$stat->save();
		return $stat;
	}
}