<?php

namespace app\models;

use app\components\ApiException;
use app\components\BaseModel;
use yii\helpers\VarDumper;
use yii\web\IdentityInterface;

class User extends BaseModel implements IdentityInterface
{
	public $registratorToken = null;

	/**
	 * Name of class that should be used for logging using login token from website
	 */
	const WEB_LOGIN = 'LoginToken';

	/**
	 * Name of class that should be used for logging using API
	 */
	const API_LOGIN = 'RegistratorToken';


	public $passwordCopy;
	const WEAK = 0;
	const STRONG = 1;

	public static function tableName()
	{
		return 'user';
	}

	public static function findByUsername($username)
	{
		return static::findOne(['username' => $username]);
	}

	public function rules()
	{
		return [
			[['username', 'mail', 'password'], 'required', 'on' => 'registration'],
			[['name', 'surname', 'username', 'password', 'tos_agreement'], 'required'],
			[['username'], 'unique'],
			[['tos_agreement', 'gdpr_agreement'], 'integer', 'integerOnly' => true],
			[['mail'], 'email'],
			//[['password'], 'passwordStrength', 'strength'=>self::STRONG],
		];
	}

	public function fields()
	{
		$fields = parent::fields();


		unset($fields['salt']);
		unset($fields['auth_key']);
		unset($fields['password']);

		return $fields;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function findIdentity($id)
	{
		return static::findOne($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		return static::findOne(['access_token' => $token]);
	}

	public function generateLoginToken()
	{
		$lt = new LoginToken(['user' => $this]);
		$lt->save();
		return $lt->getAsJWTToken();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateAuthKey($authKey)
	{
		return $this->auth_key === $authKey;
	}

	public function hashPassword($password)
	{
		return hash('sha512', $password.$this->salt);
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return $this->password == hash('sha512',$password.$this->salt);
	}


	public function passwordStrength($attribute,$params)
	{
		if ($params['strength'] === self::WEAK)
			$pattern = '/^(?=.*[a-zA-Z0-9]).{5,}$/';
		elseif ($params['strength'] === self::STRONG)
			$pattern = '/^(?=.*\d(?=.*\d))(?=.*[a-zA-Z](?=.*[a-zA-Z])).{5,}$/';

		if(!preg_match($pattern, $this->$attribute))
			$this->addError($attribute, 'your password is not strong enough!');
	}


	public function beforeSave($insert)
	{
		if ($insert)
		{
			$this->auth_key = \Yii::$app->security->generateRandomString();
			$this->salt = \Yii::$app->security->generateRandomString().time();
			$this->password = hash('sha512', $this->password.$this->salt);
		}
		return parent::beforeSave($insert);
	}

	public function afterSave($insert, $changedAttributes) {
		if (!$insert)
		{
			if (isset($changedAttributes['password']))
			{
				$this->updateAttributes(['password_changed' => date('Y-m-d H:i:s')]);
			}
			if (isset($changedAttributes['mail']))
			{
				$this->updateAttributes(['mail_changed' => date('Y-m-d H:i:s')]);
			}
		}
		return parent::afterSave($insert, $changedAttributes);
	}



	public function getLoginTokens()
	{
		return $this->hasMany(LoginToken::className(), ['user_id' => 'id']);
	}

	public function fullName()
	{
		return $this->name.' '.$this->surname;
	}

	public function hasRole($role)
	{
		$authManager = \Yii::$app->getAuthManager();
		return $authManager->getAssignment($role, $this->id) ? true : false;
	}

	public static function findByAccessToken($validationMethod, $data)
	{
		$modelName = '';
		$model = null;

		$modelName = self::getClassPath().$validationMethod;
		$model = $modelName::findByToken($data);
		if (!$model || $model->isExpired())
		{
			throw new ApiException(403);
		}

		if ($validationMethod === self::API_LOGIN)
		{
			// validate login data (ip address)
			VarDumper::dump(\Yii::$app->request->ipHeaders);die;
		}

		return $model;
	}

	public static function findById($id)
	{
		return User::findOne(['id' => $id]);
	}
}
