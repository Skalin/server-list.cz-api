<?php

namespace app\modules\v1\models;

use app\components\BaseModel;

/**
 * This is the model class for table "game".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $stats_list
 * @property int $position
 * @property int $active
 */
class Service extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'position'], 'required'],
            [['description'], 'string'],
            [['position', 'active'], 'integer'],
            [['name'], 'string', 'max' => 255],
			[['stats_list'], 'string'],
        ];
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
            'position' => 'Position',
            'active' => 'Active',
        ];
    }

    public function fields()
	{
		$fields = parent::fields();
		unset($fields['category_id']);
		unset($fields['stats_list']);
		return $fields;
	}

	public function getServers()
	{
		return $this->hasMany(Server::className(), ['game_id' => 'id']);
	}

	public static function findById($id)
	{
		return Service::findOne(['id' => $id]);
	}

	public function getQueryClass()
	{
		return $this->shortcut.'Query';

	}

	public function getStatisticsClasses()
	{
		return empty($this->stats_list) ? [] : json_decode($this->stats_list, true);
	}
}
