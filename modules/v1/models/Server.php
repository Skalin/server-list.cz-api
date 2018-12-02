<?php

namespace app\modules\v1\models;


/**
 * This is the model class for table "server".
 *
 * @property int $id
 * @property string $name
 * @property string $ip
 * @property string $domain
 * @property integer $port
 * @property integer $query_port
 * @property integer $game_id
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
			[['game_id', 'query_port', 'port'], 'integer', 'integerOnly' => true],
			['game_id', 'validateGame'],
            [['ip'], 'ip'],
            [['name'], 'string', 'max' => 255],
			['servers', 'safe'],
			['pingStatistics', 'safe'],
        ];
    }


    public function validateGame($attribute, $params, $validator)
	{
		if (!Game::findById($this->game_id))
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

	public function getGame()
	{
		return $this->hasOne(Game::className(), ['game_id' => 'id']);
	}

	public function findById($id)
	{
		return Server::findOne(['id' => $id]);
	}

	public function getPingStatistics()
	{
		return PingStat::findAll(['server_id' => $this->id]);
	}
}
