<?php

namespace app\models;

use app\components\BaseToken;
use Firebase\JWT\JWT;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

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
		return
		[
			[['user_id'], 'required'],
			[['id', 'user_id'], 'number', 'integerOnly' => true],
			[['expiration', 'token', 'name', 'surname', 'issue_date'], 'safe'],
			[['expiration', 'issue_date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
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
		$fields['iss'] = 'http://api.server-list.cz';
		$fields['aud'] = 'http://server-list.cz';
		$fields['name'] = $this->user->name;
		$fields['surname'] = $this->user->surname;
		$fields['iat'] = strtotime($this->issue_date);
		$fields['exp'] = strtotime($this->expiration);
		$fields['token'] = $this->token;

		return JWT::encode($fields, self::LOGIN_TOKEN_KEY);
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


	public function isAfterIssueTime()
	{
		return (strtotime(date('Y-m-d H:i:s')) < strtotime($this->issue_date));
	}

	public function isInvalid()
	{
		return ($this->isExpired() || $this->isAfterIssueTime());
	}

	public function expire()
	{
		$this->expiration = date('Y-m-d h:i:s');
		return $this->save() ? true : false;
	}
}
