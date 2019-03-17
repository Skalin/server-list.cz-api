<?php

namespace app\modules\v1\models;

use app\components\BaseModel;
use app\components\queries\MCQuery;
use app\components\queries\CSGOQuery;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "server".
 *
 * @property int $id
 * @property string $name
 * @property string $ip
 * @property string $domain
 * @property integer $port
 * @property integer $query_port
 * @property integer $service_id
 * @property integer $registrator_id
 * @property integer $user_id
 * @property int $active
 */
class Server extends BaseModel
{

	const MC = 1;
	const CSGO = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'server';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
			[['name', 'service_id'], 'required'],
			[['service_id', 'query_port', 'port', 'registrator_id', 'user_id'], 'integer', 'integerOnly' => true],
			[['service_id'], 'validateService'],
			[['ip'], 'ip'],
			[['user', 'registrator'], 'safe'],
			[['user_id', 'registrator_id'], 'validateUser'],
			[['name'], 'string', 'max' => 100],
			[['ip', 'domain'], 'validateIp'],
			[['pingStatistics', 'availableStatistics', 'service'], 'safe']
        ];
    }


    public function validateUser($attribute, $params, $validator)
	{
		if (empty($this->registrator_id) && $this->user_id)
		{
			$this->addError($attribute, 'User or registrator must be existing!');
		}
	}

    public function validateIp($attribute, $params, $validator)
	{
		if (empty($this->ip) && empty($this->domain))
		{
			$this->addError($attribute, 'At least IP must be filled');
		}

		if (self::findByAddress($this->ip, $this->domain, $this->port))
		{
			$this->addError($attribute, 'Server already exists!');
		}
	}

    public function validateService($attribute, $params, $validator)
	{
		if (!Service::findById($this->service_id))
		{
			$this->addError($attribute, 'The requested service was not found.');
		}

	}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'active' => 'Active',
        ];
    }

    public function fields()
	{
		$fields = parent::fields();

		unset($fields['password']);
		return $fields;
	}

	public function getService()
	{
		return $this->hasOne(Service::className(), ['service_id' => 'id']);
	}

	public static function findById($id)
	{
		return Server::findOne(['id' => $id]);
	}

	public function getPingStatistics()
	{
		return PingStat::findAll(['server_id' => $this->id]);
	}

	protected function getAvailableStatistics()
	{
		return Service::findById($this->service_id)->getStatisticsClasses();
	}


	public function destroyOldStatistics()
	{
		$stats = $this->getAvailableStatistics();

		foreach ($stats as $stat)
		{
			$className = $this->getClassPath().$stat;
			$className::destroyOldStatistics($this->id);
		}
	}

	public function generateStatistics()
	{
		$stats = $this->getAvailableStatistics();
		$service = Service::findById($this->service_id);
		$queryClass = $this->queryNamespace.'\\'.$service->getQueryClass();
		$result = $queryClass::query($this);

		foreach ($stats as $stat)
		{
			$className = $this->getClassPath().$stat;
			echo "Generating {$stat} statistics for server {$this->id}: {$this->name}\n";
			if (is_null($className::generateStat($this->id, $result)))
			{
				echo "Stat: {$stat} could not be generated for server {$this->id}: {$this->name}\n";
			}
		}
	}

	public function setTimeouts($timeout)
	{
		$this->timeout += $timeout;
		if ($timeout > $this->maximumTimeouts)
		{
			$this->active = 0;
		}
	}

	public function afterFind()
	{
		$this->oldAttributes = $this->attributes;
	}

	public function beforeSave($insert)
	{
		if (!$this->isNewRecord)
		{
			if ($this->oldAttributes->active < $this->active)
			{
				$this->timeout = 0;
			}

		}
		return parent::beforeSave($insert);
	}

	public static function findByAddress($ip, $domain, $port)
	{
		$server = null;

		$server = static::findOne(['ip' => $ip, 'port' => $port]);
		if ($server)
			return $server;

		$server = static::findOne(['domain' => $domain, 'port' => $port]);

		if ($server)
			return $server;

		return false;
	}
}
