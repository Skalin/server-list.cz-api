<?php

namespace app\models;

use app\components\BaseToken;

class LoginToken extends BaseToken
{

	public static function tableName()
	{
		return 'login_token';
	}

	public function rules()
	{
		$rules = parent::rules();
		return $rules;
	}
}
