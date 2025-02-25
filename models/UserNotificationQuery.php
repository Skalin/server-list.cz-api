<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserNotification]].
 *
 * @see UserNotification
 */
class UserNotificationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function user($user)
	{
		return $this->andWhere(['user_id' => $user]);
	}


    public function service($service)
    {
        return $this->andWhere(['like', 'objectArray', "\"services\": {$service}"]);
    }


	public function server($server)
    {
        return $this->andWhere(['like', 'objectArray', "\"servers\": {$server}"]);
    }

    /**
     * {@inheritdoc}
     * @return UserNotification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserNotification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
