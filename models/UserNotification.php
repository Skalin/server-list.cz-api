<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\VarDumper;

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
            [['content', 'objectArray'], 'string'],
			[['read'], 'boolean'],
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

    public static function notify($userIds = [], $title, $message, $data)
	{

		if (empty($userIds))
		{
			$userIds = new ActiveDataProvider([
				'query' => User::find()
					->select('id')
					->all(),
			]);
		}


		foreach ($userIds as $id)
		{
			VarDumper::dump($id);die;
			$n = new UserNotification;
			$n->user_id = $id;
			$n->content = $message;
			$n->date = new Expression('NOW()');
			$n->title = $title;
			$n->objectArray = $data;
			$n->save();
		}

	}
}
