<?php

namespace app\models;

use app\components\BaseToken;
use Firebase\JWT\JWT;

/**
 * Class LoginToken
 *
 *
 * @property datetime $issue_date
 * @property string $token
 * @property datetime $expiration
 * @property integer $user_id
 *
 * @property User $user
 *
 * @package app\models
 *
 */

class LoginToken extends BaseToken
{

	const LOGIN_TOKEN_KEY = 'fjkajkfwaf2/r2*/q42q-*r42498f498a4f89z1x65z1vz-*z-v*s5z+wr42wt[g=p;][/';

	public $name;
	public $surname;

	public $iss;
	public $aud;
	public $iat;


	public static function tableName()
	{
		return 'login_token';
	}

	public function rules()
	{

		return [
			[['id', 'user_id'], 'number', 'integerOnly' => true],
			[['expiration', 'token', 'name', 'surname', 'issue_date'], 'safe'],
		];
	}

	public function __construct(array $config = [])
	{

		$this->link('user', $config['user']);
		return parent::__construct();
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
		$fields['iss'] = 'http://api.server-list.cz';
		$fields['aud'] = 'http://server-list.cz';
		$fields['iat'] = $this->issue_date;
		$fields['name'] = $this->user->name;
		$fields['surname'] = $this->user->surname;
		$fields['exp'] = $this->expiration;
		$fields['token'] = $this->token;

		$key = 'fjkajkfwaf2/r2*/q42q-*r42498f498a4f89z1x65z1vz-*z-v*s5z+wr42wt[g=p;][/';

		return JWT::encode($fields, $key);
	}


	public function beforeSave($insert)
	{
		if ($this->isNewRecord)
		{
			$date = new \DateTime(date('Y-m-d H:i:s'));
			$this->issue_date = $date->format('Y-m-d H:i:s');
			$date->add(new \DateInterval('P30D'));
			$this->expiration = $date->format('Y-m-d H:i:s');
			$this->token = \Yii::$app->security->generateRandomString();
		}

		return parent::beforeSave($insert);
	}
}
