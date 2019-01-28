<?php

namespace app\modules\v1\models;

use app\modules\v1\models\PingStat;

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
 * @property int $active
 */
class Server extends \yii\db\ActiveRecord
{
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
            [['name'], 'required'],
			[['service_id', 'query_port', 'port'], 'integer', 'integerOnly' => true],
			['service_id', 'validateService'],
            [['ip'], 'ip'],
            [['name'], 'string', 'max' => 255],
			['servers', 'safe'],
			['pingStatistics', 'safe'],
        ];
    }


    public function validateService($attribute, $params, $validator)
	{
		if (!Service::findById($this->service_id))
		{
			$this->addError($attribute, 'The requested game was not found.');
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
		return Service::findById($this->service_id)->getStatistics();
	}


	public function destroyOldStatistics()
	{
		$stats = $this->getAvailableStatistics();

		foreach ($stats as $stat)
		{
			$stat::destroyOldStatistics($this->id);
		}
	}

	public function generateStatistics()
	{
		$stats = $this->getAvailableStatistics();

		foreach ($stats as $stat)
		{
			echo "Generating {$stat} statistics for server {$this->id}: {$this->name}\n";
			$stat::generateStat($this->id);
		}
	}


}
