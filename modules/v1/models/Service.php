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
	        [['image_url', 'thumbnail_image_url'], 'safe'],
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
	        'image_url' => 'Image',
	        'thumbnail_image_url' => 'Thumbnail',
            'active' => 'Active',
        ];
    }

    public function fields()
	{
		$fields = parent::fields();
		unset($fields['category_id']);
		unset($fields['stats_list']);
		$fields['thumbnailImageUrl'] = function ($model) {
			return $model->thumbnail_image_url;
		};
		$fields['imageUrl'] = function ($model) {
			return $model->image_url;
		};
		unset($fields['image_url']);
		unset($fields['thumbnail_image_url']);
		$fields['serverCount'] = function ($model)
		{
			return Server::find()->service($this->id)->loggable()->count();
		};
		return $fields;
	}



	public function getServers()
	{
		return $this->hasMany(Server::className(), ['service_id' => 'id']);
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
