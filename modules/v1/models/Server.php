<?php

namespace app\modules\v1\models;

use Yii;

/**
 * This is the model class for table "server".
 *
 * @property int $id
 * @property string $name
 * @property string $address
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
            [['description'], 'string'],
            [['active'], 'integer'],
            [['name'], 'string', 'max' => 255],
			['servers', 'safe']
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
            'active' => 'Active',
        ];
    }

	public function getGame()
	{
		return $this->hasOne(Game::className(), ['game_id' => 'id']);
	}
}
