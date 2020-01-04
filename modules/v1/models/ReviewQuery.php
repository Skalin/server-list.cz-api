<?php

namespace app\modules\v1\models;

use yii\db\ActiveQuery;
use yii\helpers\VarDumper;

/**
 * This is the ActiveQuery class for [[Review]].
 *
 * @see Review
 */
class ReviewQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Review[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Review|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

	/**
	 * @param $type
	 * @return ReviewQuery
	 */
    public function type($type)
	{
		return $this->leftJoin('{{user}} u', 'user_id = u.id')
			->andWhere(['u.is_reviewer' => $type]);
	}

	public function rating()
	{
		return $this->average('rating');
	}

	public function server($id)
	{
		return $this->andWhere(['server_id' => $id]);
	}

	public function user($id)
    {
        return $this->andWhere(['user_id' => $id]);
    }
}
