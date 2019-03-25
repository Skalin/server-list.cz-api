<?php

namespace app\modules\v1\models;

/**
 * This is the ActiveQuery class for [[Server]].
 *
 * @see Server
 */
class ServerQuery extends \yii\db\ActiveQuery
{

	public function service($service_id)
	{
		return $this->andWhere(['service_id' => $service_id]);
	}

    /**
     * {@inheritdoc}
     * @return Server[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Server|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
