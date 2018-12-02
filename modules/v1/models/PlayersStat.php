<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 02.12.2018
 * Time: 15:17
 */

namespace app\modules\v1\models;

use \app\models\StatModel;

class PlayersStat extends StatModel
{
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
			[['value'], 'number', 'integerOnly' => true]
		];
		$rules = array_merge($rules, $newRules);
		return $rules;
	}

}