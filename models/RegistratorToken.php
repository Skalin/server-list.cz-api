<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 27.02.2019
 * Time: 22:36
 */

namespace app\models;

use app\components\BaseToken;

class RegistratorToken extends BaseToken
{

	public static function tableName()
	{
		return 'registrator_token';
	}

	public function rules()
	{
		$rules = parent::rules();
		$newRules = [
			[['description', 'ip_list'], 'safe'],
			[['active'], 'boolean'],
		];
		$rules = array_merge($rules, $newRules);
		return $rules;
	}
}