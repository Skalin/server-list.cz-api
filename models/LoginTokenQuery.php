<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[LoginToken]].
 *
 * @see LoginToken
 */
class LoginTokenQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return LoginToken[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return LoginToken|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function user($user)
	{
		return $this->andWhere(['user_id' => $user]);
	}

	public function active()
	{
		$now = date('Y-m-d h:i:s');
		return $this->andWhere("`expiration` > \"$now\"");
	}
}
