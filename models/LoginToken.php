<?php

namespace app\models;

use app\components\BaseToken;

class LoginToken extends BaseToken
{

	public $name;
	public $surname;

	public static function tableName()
	{
		return 'login_token';
	}

	public function rules()
	{

		return [
			[['id', 'user_id'], 'number', 'integerOnly' => true],
			[['expiration', 'token', 'name', 'surname'], 'safe'],
		];
	}

	public function getFullName()
	{
		return $this->name.' '.$this->surname;
	}

	public function get()
	{
		unset($this->id);
		return array_merge($this->attributes, ['name' => $this->user->name, 'surname' => $this->user->surname]);
	}
}
