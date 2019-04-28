<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_notification".
 *
 * @property int $id
 * @property int $user_id
 * @property resource $read
 * @property string $title
 * @property string $date
 * @property string $content
 * @property string $objectArray
 */
class UserNotification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['read', 'content', 'objectArray'], 'string'],
            [['date'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'read' => 'Read',
            'title' => 'Title',
            'date' => 'Date',
            'content' => 'Content',
            'objectArray' => 'Object Array',
        ];
    }

    public function fields()
	{
		$fields = parent::fields();
		unset($fields['user_id']);
		return $fields;
	}


	/**
     * {@inheritdoc}
     * @return UserNotificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserNotificationQuery(get_called_class());
    }
}
