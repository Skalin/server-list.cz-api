<?php

namespace app\models;

use app\components\BaseToken;
use Firebase\JWT\JWT;

class LoginToken extends BaseToken
{

	public $name;
	public $surname;

	public $iss;
	public $aud;
	public $iat;


	public static function tableName()
	{
		return 'login_token';
	}

	public function __construct(array $config = ['user'])
	{
		$date = new \DateTime(date('Y-m-d H:i:s'));
		$date->add(new \DateInterval('P30D'));
		$this->expiration = $date->format('Y-m-d H:i:s');
		$this->token = \Yii::$app->security->generateRandomString();
		$this->link('user', $config['user']);

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


	public function fields()
	{
		$fields = parent::fields();

		$fields['name'] = function ($model) {
			return $model->user->name;
		};
		$fields['surname'] = function ($model) {
			return $model->user->surname;
		};

		unset($fields['id']);

		return $fields;
	}


	public function getAsJWTToken()
	{
		$fields = $this;
		$fields['iss'] = 'http://api.server-list.cz';
		$fields['aud'] = 'http://server-list.cz';
		$fields['iat'] = date('U');
		$fields['name'] = $this->user->name;
		$fields['surname'] = $this->user->surname;
		$fields['expiration'] = $this->expiration;


		$key = 'fjkajkfwaf2/r2*/q42q-*r42498f498a4f89z1x65z1vz-*z-v*s5z+wr42wt[g=p;][/';

		return JWT::encode($fields, $key);
	}
}
