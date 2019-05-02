<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 02.05.2019
 * Time: 23:45
 */

namespace app\models;


use app\modules\v1\models\Server;
use yii\base\Model;

class ServerFindForm extends Model
{

	public $ip;
	public $port;
	public $domain;

	private $_model = false;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			// username and password are both required
			[['ip', 'port'], 'required'],
			['ip', 'findServer'],
		];
	}

	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array $params the additional name-value pairs given in the rule
	 */
	public function findServer($attribute, $params)
	{
		if (!$this->hasErrors()) {

			$server = $this->getServer();

			if (!$server) {
				$this->addError($attribute, 'Incorrect username or password.');
			}
			$this->_model = $server;
		}
	}


	/**
	 * Finds user by [[username]]
	 *
	 * @return Server|null
	 */
	public function getServer()
	{
		if ($this->_model === false) {
			$this->_model = Server::findByAddress($this->ip, $this->domain, $this->port);
		}

		return $this->_model;
	}
}