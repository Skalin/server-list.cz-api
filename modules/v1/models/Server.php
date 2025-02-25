<?php

namespace app\modules\v1\models;

use app\components\BaseModel;
use app\models\StatModel;
use app\components\queries\MCQuery;
use app\components\queries\CSGOQuery;
use app\models\User;
use phpDocumentor\Reflection\Types\This;
use app\models\UserNotification;
use Codeception\Lib\Notification;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\HtmlPurifier;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "server".
 *
 * @property int $id
 * @property string $name
 * @property string $ip
 * @property string $domain
 * @property string $description
 * @property string $image_url
 * @property integer $port
 * @property integer $query_port
 * @property integer $service_id
 * @property integer $registrator_id
 * @property integer $show_port
 * @property integer $user_id
 * @property string $state
 */
class Server extends BaseModel
{

    private $timeouts;

	public $stats;
	public $imageUrl;

	const MC = 1;
	const CSGO = 2;

	const MAX_TIMEOUTS = 3;

	const STATE_DISABLED = 0;
	const STATE_ACTIVE = 1;
	const STATE_LOGGING_ONLY = 2;

	public static $states = [
	    self::STATE_ACTIVE => 'Active',
        self::STATE_DISABLED => 'Disabled',
        self::STATE_LOGGING_ONLY => 'Logging Only',
    ];

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
			[['name', 'service_id', 'ip', 'port'], 'required'],
			[['service_id', 'query_port', 'port', 'registrator_id', 'user_id', 'show_port', 'monitoring_chunk', 'players_value'], 'integer', 'integerOnly' => true],
			[['service_id'], 'validateService'],
			[['ip'], 'ip'],
			[['port'], 'validatePort'],
			[['user', 'registrator', 'domain'], 'safe'],
			[['user_id', 'registrator_id'], 'validateUser'],
			[['name'], 'string', 'max' => 100],
			[['image_url', 'description', 'created_at', 'updated_at'], 'safe'],
			[['ip'], 'validateIp'],
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

		if (($model = self::findByAddress($this->ip, $this->domain, $this->port)) && ($model->id != $this->id))
		{
			$this->addError($attribute, 'Server already exists!');
		}
	}

	public function validatePort($attribute, $params, $validator)
	{
		$port = intval($this->port);

		if (0 > $port && $port > 65535)
		{
			$this->addError('Port must be in between of values 0 - 65535');
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
            'state' => 'Status',
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
		unset($fields['state']);
        unset($fields['monitoring_chunk']);
        unset($fields['query_port']);

		$fields['createdAt'] = function($model) {
				return $model->created_at;
			};
		unset($fields['created_at']);

		$fields['updatedAt'] = function($model) {
			return $model->updated_at;
		};
		unset($fields['updated_at']);

        unset($fields['updatedAt']);

		if ($this->image_url)
			$fields['imageUrl'] = function($model) {
				return $model->image_url;
			};
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

	public function generateStatistics($startDate, $overrideSaving = false)
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
				\Yii::debug("Stat: {$stat} could not be generated for server {$this->id}: {$this->name} because server is OFFLINE.");
				return false;
			}
			else if (is_null($className::generateStat($startDate, $this->id, $result)))
			{
				\Yii::debug("Stat: {$stat} could not be generated for server {$this->id}: {$this->name}");
				$failedGeneration++;
			}
		}

		$this->timeouts++;
		if ($failedGeneration == count($stats))
			return false;
		return true;
	}

	public function setTimeouts($timeout)
	{
		$this->timeout = $timeout;
        if ($timeout >= self::MAX_TIMEOUTS)
		{
			$this->state = 0;
		}
	}

	public function afterFind()
	{
		$this->oldAttributes = $this->attributes;
	}

	public function beforeSave($insert)
	{
	    $this->description = HtmlPurifier::process($this->description);

	    if ($this->isNewRecord && is_null($this->state))
        {
            $this->state = self::STATE_ACTIVE;
        }
		if (!$this->isNewRecord)
		{
			if ($this->oldAttributes['state'] < $this->state)
			{
				$this->timeout = 0;
			}

			$this->updated_at = new Expression('NOW()');
		}
		return parent::beforeSave($insert);
	}

	public static function findByAddress($ip, $domain, $port)
	{
		$server = null;

		$server = self::findOne(['ip' => $ip, 'port' => $port]);
		if ($server)
			return $server;

		return false;
	}

	public static function findByChunk($chunk)
	{
		$servers = null;
		$servers = self::findAll(['monitoring_chunk' => $chunk, 'state' => 1]);
		return $servers;

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

	public function afterSave($insert, $changedAttributes)
	{/*
		$pid = pcntl_fork();
		if ($pid == -1)
			return;
		else if ($pid) {

		}
		else {*/
			$title = "Novinka!";
			if (!$insert)
			{
				$title = "Update serveru {$this->name}!";
				$message = "Server {$this->name} byl upraven. Podívejte se na novinky!";
			}
			else
				$message = "Server {$this->name} byl právě přidán! Mrkněte oč se jedná!";

			$data = "{\"services\": {$this->service_id}, \"servers\": {$this->id}}";
			if (!empty($changedAttributes))
				UserNotification::notify([], $title, $message, $data);
		/*}*/
	}


	public function afterDelete()
    {
        parent::afterDelete();
        $notifications = UserNotification::find()->server($this->id)->all();
        foreach ($notifications as $notification)
            $notification->delete();
        return true;
    }


	public function calculateReviews()
	{
		$adminReviewsQuery = Review::find()
			->server($this->id)
			->type(1);
		$adminsRating = $adminReviewsQuery->rating();

		$userReviewsQuery = Review::find()
			->server($this->id)
			->type(0);
		$usersRating = $userReviewsQuery->rating();

		return [
			'admins' => [
				'rating' => $adminsRating,
				'reviews' => $adminReviewsQuery->all(),
			],
			'users' => [
				'rating' => $usersRating,
				'reviews' => $userReviewsQuery->all(),
			]
		];
	}
}
