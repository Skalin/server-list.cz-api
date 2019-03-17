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


		return $fields;
	}

}
