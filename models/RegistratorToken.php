<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 27.02.2019
 * Time: 22:36
 */

namespace app\models;

use app\components\BaseToken;
use yii\helpers\VarDumper;

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



	public function checkIPAddress($address)
	{

		$ipsArray = explode(',', $this->ip_list);
		$ips = [];
		if (!count($ipsArray))
			return true;

		foreach ($ipsArray as $ip)
		{
			$val = trim($ip);
			if (!empty($val))
				$ips[] = $val;
		}

		return in_array($address, $ips);
	}
}