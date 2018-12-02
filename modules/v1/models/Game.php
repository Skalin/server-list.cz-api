<?php

namespace app\modules\v1\models;

/**
 * This is the model class for table "game".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $position
 * @property int $active
 */
class Game extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game';
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

	public function getServers()
	{
		return $this->hasMany(Server::className(), ['game_id' => 'id']);
	}

	public function findById($id)
	{
		return Game::findOne(['id' => $id]);
	}
}
