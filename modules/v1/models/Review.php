<?php

namespace app\modules\v1\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "review".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $rating
 * @property int $user_id
 * @property int $server_id
 *
 * @property User $user
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['rating', 'user_id', 'server_id'], 'integer'],
            [['rating', 'integer', 'min' => 0, 'max' => 100]],
            [['title'], 'string', 'max' => 255],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
			[['server_id'], 'exist', 'skipOnError' => true, 'targetClass' => Server::className(), 'targetAttribute' => ['server_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'rating' => Yii::t('app', 'Rating'),
            'user_id' => Yii::t('app', 'User ID'),
			'server_id' => Yii::t('app', 'Server ID'),
        ];
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(User::class, ['id' => 'user_id']);
	}


	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getServer()
	{
		return $this->hasOne(Server::class, ['id' => 'server_id']);
	}

	/**
     * {@inheritdoc}
     * @return ReviewQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReviewQuery(get_called_class());
    }
}
