<?php

namespace app\modules\v1\models;

/**
 * This is the ActiveQuery class for [[Server]].
 *
 * @see Server
 */
class ServerQuery extends \yii\db\ActiveQuery
{

    public function loggable()
    {
        return $this->andWhere(['state', 'in', [Server::STATE_ACTIVE, Server::STATE_LOGGING_ONLY]]);
    }

    public function chunk($chunk)
    {
        return $this->andWhere(['monitoring_chunk', $chunk]);
    }

    public function active($state = Server::STATE_ACTIVE)
    {
        return $this->andWhere(['state' => $state]);
    }

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
