<?php

namespace app\modules\v1\models;

use app\components\BaseModel;
use app\models\StatModel;
use app\components\queries\MCQuery;
use app\components\queries\CSGOQuery;
use app\models\User;
use phpDocumentor\Reflection\Types\This;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "server".
 *
 * @property int $id
 * @property string $name
 * @property string $ip
 * @property string $domain
 * @property string $image_url
 * @property integer $port
 * @property integer $query_port
 * @property integer $service_id
 * @property integer $registrator_id
 * @property integer $user_id
 * @property int $active
 */
class Server extends BaseModel
{

	public $stats;
	public $imageUrl;

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
			[['name', 'service_id', 'ip', 'port', 'domain'], 'required'],
			[['service_id', 'query_port', 'port', 'registrator_id', 'user_id'], 'integer', 'integerOnly' => true],
			[['service_id'], 'validateService'],
			[['ip'], 'ip'],
			[['user', 'registrator'], 'safe'],
			[['user_id', 'registrator_id'], 'validateUser'],
			[['name'], 'string', 'max' => 100],
			[['image_url'], 'safe'],
			[['ip', 'domain'], 'validateIp'],
			[['pingStatistics', 'availableStatistics', 'service'], 'safe'],
			[['statusStatistics', 'availableStatistics', 'service'], 'safe'],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
			[['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Service::class, 'targetAttribute' => ['service_id' => 'id']],
			[['registrator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['registrator_id' => 'id']],
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
			'domain' => 'Domain',
			'ip' => 'Ip',
			'password' => 'Password',
			'port' => 'Port',
			'query_port' => 'Query Port',
			'service_id' => 'Service ID',
			'registrator_id' => 'Registrator ID',
			'user_id' => 'User ID',
			'image_url' => 'Image Url',
        ];
    }

    public function fields()
	{
		$fields = parent::fields();

		unset($fields['password']);
		unset($fields['user_id']);
		unset($fields['registrator_id']);
		/*if (!($this->image_url))
			$fields['imageUrl'] = function($model) {
				return $this->getImageUrl();
			};*/
		unset($fields['image_url']);

		$fields['stats'] = function($model) {
					return $this->getLatestStatistics();
				};

		return $fields;
	}


	private function getLatestStatistics()
	{
		$availableStatistics = $this->getAvailableStatistics();
		$stats = [];
		foreach ($availableStatistics as $availableStatistic)
		{
			$statClass = $this->getClassPath().$availableStatistic;
			$stat = $statClass::find()->server($this->id)->latest()->one();
			$stats[$availableStatistic] = $stat;
		}
		return $stats;
	}


	private function getImageUrl()
	{
		if (!$this->image_url)
		{
			$this->image_url = method_exists($this->getQueryPath($this->service_id), 'getImage') ? $this->getQueryPath($this->service_id)::getImage($this) : null;
			$this->save();
		}
		return $this->image_url;
	}

	public function getStatusStatistics()
	{
		return $this->hasMany(StatusStat::class, ['server_id' => 'id']);
	}

	public function getPlayersStatistics()
	{
		return $this->hasMany(StatusStat::class, ['server_id' => 'id']);
	}

	public function getService()
	{
		return $this->hasOne(Service::className(), ['id' => 'service_id']);
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

	public function generateStatistics($overrideSaving = false)
	{
		$stats = $this->getAvailableStatistics();
		$service = Service::findById($this->service_id);
		$queryClass = $this->queryNamespace.'\\'.$service->getQueryClass();
		$result = $queryClass::query($this);
		$failedGeneration = 0;


		foreach ($stats as $stat)
		{
			$className = $this->getClassPath().$stat;
			echo "Generating {$stat} statistics for server {$this->id}: {$this->name}\n";
			if (!$overrideSaving && isset($result['status']) && $result['status'] == 0)
			{
				echo "Stat: {$stat} could not be generated for server {$this->id}: {$this->name} because server is OFFLINE.\n";
				return false;
			}
			else if (is_null($className::generateStat($this->id, $result)))
			{
				echo "Stat: {$stat} could not be generated for server {$this->id}: {$this->name}\n";
				$failedGeneration++;
			}
		}

		if ($failedGeneration == count($stats))
			return false;


		return true;
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

		$server = self::findOne(['ip' => $ip, 'port' => $port]);
		if ($server)
			return $server;

		$server = self::findOne(['domain' => $domain, 'port' => $port]);

		if ($server)
			return $server;

		return false;
	}

	/**
	 * {@inheritdoc}
	 * @return ServerQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new ServerQuery(get_called_class());
	}

	public function setService($id)
	{
		$this->service_id = $id;
	}

	public function getAllStats()
	{
		$stats = [];
		$statsClasses = $this->service->getStatisticsClasses();
		foreach ($statsClasses as $statsClass)
		{
			$className = $this->getClassPath().$statsClass;
			$statistics = $className::find()->server($this->id)->all();
			$stats[$statsClass] = $statistics;
		}
		return $stats;
	}
}
