<?php

namespace app\models;

use app\components\ApiException;
use app\components\BaseModel;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use yii\helpers\VarDumper;
use yii\web\IdentityInterface;


/**
 * Class User
 *
 * @property string $name
 * @property string $surname
 *
 * @package app\models
 */
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

	public static function findByMail($mail)
	{
		return static::findOne(['mail' => $mail]);
	}

	public static function findByUsername($username)
	{
		return static::findOne(['username' => $username]);
	}

	public function rules()
	{
		return [
			//[['username', 'mail', 'password'], 'required', 'on' => 'registration'],
			[['username', 'name', 'surname', 'mail', 'password', 'tos_agreement'], 'required'],
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
		$lt = new LoginToken();
		$lt->user_id = $this->id;
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


	public function getServers()
	{
		return $this->hasMany(Server::class, ['user_id' => 'id']);
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


	/**
	 * @param $validationMethod
	 * @param $token
	 * @return LoginToken|RegistratorToken
	 */
	public static function findAccessToken($validationMethod, $token)
	{
		$modelName = self::getClassPath().$validationMethod;
		$model = $modelName::findByToken($token);
		return $model;
	}

	public static function findByAccessToken($validationMethod, $data)
	{
		$modelName = '';
		$model = null;

		if ($validationMethod === self::API_LOGIN)
		{
			$model = RegistratorToken::findByToken($data);
			if (!$model)
			{
				throw new ApiException(401, 'User not existing');
			}

			if ($model->isExpired())
			{
				throw new ApiException(403, 'Token expired');
			}

			$ip = \Yii::$app->request->getUserIP();
			if (!$model->checkIPAddress($ip))
			{
				throw new ApiException(412, 'Not allowed from this location');
			}
		}
		else
		{

			$token = JWT::decode($data, LoginToken::LOGIN_TOKEN_KEY, array("HS256"));

			$model = self::findAccessToken($validationMethod, $token->token);
			if (!$model || $model->isInvalid())
			{
				throw new ApiException(403, 'Token is expired.');
			}
		}

		return $model->user;
	}

	public static function findById($id)
	{
		return User::findOne(['id' => $id]);
	}
}
