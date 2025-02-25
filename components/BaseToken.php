<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 27.02.2019
 * Time: 22:56
 */

namespace app\components;


use app\models\User;

class BaseToken extends BaseModel
{


	public function rules()
	{
		return [
			[['token', 'user_id', 'expiration'], 'required'],
			[['token'], 'unique'],
			[['expiration'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
			[['user_id'], 'integer', 'integerOnly' => true],
		];
	}

	public function isExpired()
	{
		if ($this->expiration === null)
			return false;
		return ($this->expiration && strtotime(date('Y-m-d H:i:s')) > strtotime($this->expiration));
	}

	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}


	public static function findByToken($token)
	{
		return get_called_class()::findOne(['token' => $token]);
	}
}