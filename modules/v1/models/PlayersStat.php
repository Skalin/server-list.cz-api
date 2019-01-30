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

	public function generateStat($server_id, $value = NULL)
	{
		$stat = parent::generateStat($server_id, $value);
		if ($value)
		{
			$stat->value = $value[self::$attribute];
			$stat->maxValue = $value[self::$secondAttribute];
		}
		if (!$stat->validate())
			return null;
		$stat->save();
		return $stat;
	}
}